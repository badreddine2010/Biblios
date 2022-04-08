<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Borrow;
use App\Repository\UserRepository;
use App\Repository\BorrowRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BorrowController extends AbstractController
{
    /**
     * @Route("/borrow/all", name="app_borrow")
     */
    public function index(BorrowRepository $borrowRepository): Response
    {
        // dd($borrowRepository->findAll());
        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrowRepository->findAll(),
        ]);
    }
    /**
     * @Route("/borrow", name="borrow_app")
     */
    public function indexUser(BorrowRepository $borrowRepository, UserInterface $user): Response
    {

        $borrows = $borrowRepository->findBy(['users' => $user]);
        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrowRepository->findBy(['users' => $user]),
            'totalBorrows'=>count($borrows)
        ]);
    }
    
    
    /**
     *@Route("/borrow/add/{id}/{user}", name="borrow_add")
     */
    public function addBorrow(
        UserRepository $userRepository,
        BorrowRepository $borrowRepository,
        Book $book,
        EntityManagerInterface $em,
        User $user
        ) {
            $idBook = true;
            $borrows = $borrowRepository->findBy(['users' => $user]);
            // dd(count($borrows));
            for ($i = 0; $i < count($borrows); $i++) {
                # code...
                // echo($borrows[$i]->getBooks()->getId());
                if ($book->getId() == $borrows[$i]->getBooks()->getId()) {
                    # code...
                    $idBook = false;
                    $this->addFlash('error', "Vous l'avez déjà emprunté");
                    return $this->redirectToRoute('home');
            }
        }
        $nbreMax = 4;
        $nbreEmprunts = count($borrows);
        if ($nbreEmprunts < $nbreMax && ($idBook)) {
            # code...

            $qteRestante = $book->getAvailable();
            // '$nbreEmprunts' => $userRepository->findBy(['user'=>$user]);
            if ($qteRestante >= 1) {

                $qteRestante--;
                $book->setAvailable($qteRestante);

                $em->persist($book);
                $em->flush();
                // dd($qteRestante);
                // dd($user);

                $borrow = new Borrow();
                $borrow->setUsers($user);
                $borrow->setBooks($book);

                $now = date('Y-m-d');
                $start_date = strtotime($now);
                $end_date = strtotime('+15 day', $start_date);
                $dateRetour = date('Y-m-d', $end_date);
                $borrow->setReturnDate(new \DateTimeImmutable($dateRetour));

                $em->persist($borrow);
                $em->flush();
            }
        }else {

            $this->addFlash('error', "Vous êtes au maximum de livres empruntés");
            return $this->redirectToRoute('home');
        }
        $this->addFlash('success', "livre ajouté à votre compte");
        return $this->redirectToRoute('home');
    }

    /**
     *@Route("/returnBook/{id}/{book}", name="return_book")
     */
    public function returnBook(int $id, ManagerRegistry $doctrine, Book $book, EntityManagerInterface $em)
    {
        $qteRestante = $book->getAvailable();

        $qteRestante++;
        $book->setAvailable($qteRestante);

        $em->persist($book);
        $em->flush();

        $entityManager = $doctrine->getManager();
        $borrow = $entityManager->getRepository(Borrow::class)->find($id);

        $entityManager->remove($borrow);
        $entityManager->flush();

        return $this->redirectToRoute('borrow_app', [
            'user' => $borrow->getUsers(),
            'book' => $borrow->getBooks()
        ]);
    }
    /**
     * @Route("/checkNberBorrows/{user}",name="nber_borrows")
     */
    public function checkNberBorrows(UserInterface $user, BorrowRepository $borrowRepository)
    {

        $borrows = $borrowRepository->findBy([$user]);
        dd($borrows);
    }
}
