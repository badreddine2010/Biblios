<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Borrow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BorrowController extends AbstractController
{
    /**
     * @Route("/borrow", name="app_borrow")
     */
    public function index(): Response
    {
        return $this->render('borrow/index.html.twig', [
            'controller_name' => 'BorrowController',
        ]);
    }

     /**
     *@Route("/borrow/add/{id}/{user}", name="borrow_add") 
     */
    public function addBorrow(Book $book,EntityManagerInterface $em,User $user) {
     
         
            // dd($user);
            $borrow =new Borrow();
            
            $borrow->setUsers($user);
            $borrow->setBooks($book);
            
            $now = date('Y-m-d');
            $start_date = strtotime($now);
            $end_date = strtotime("+15 day", $start_date);
            $dateRetour=date('Y-m-d', $end_date);
            $borrow->setReturnDate(new \DateTimeImmutable($dateRetour));
            
            $em->persist($borrow);
            $em->flush();
            // }
            return $this->redirectToRoute('app_borrow');
     
    }
}
