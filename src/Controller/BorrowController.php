<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Borrow;
use App\Repository\UserRepository;
use App\Repository\BorrowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BorrowController extends AbstractController
{
    /**
     * @Route("/borrow", name="app_borrow")
     */
    public function index(BorrowRepository $borrowRepository): Response
    {
        // dd($borrowRepository->findAll());
        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrowRepository->findAll(),
        ]);
    }

    /**
     *@Route("/borrow/add/{id}/{user}", name="borrow_add")
     */
    public function addBorrow(UserRepository $userRepository,
        Book $book,
        EntityManagerInterface $em,
        User $user
    ) {
        $qteRestante = $book->getAvailable();
        // '$nbreEmprunts' => $userRepository->findBy(['user'=>$user]);
        if($qteRestante>=1){

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
        
            return $this->redirectToRoute('app_borrow');
        }
        // $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
        
        return $this->redirectToRoute('home');
        }
    }
    