<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrow;
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
     *@Route("/borrow/add/{id}", name="borrow_add") 
     */
    public function addBorrow(
        
        Book $book,
        Borrow $borrow,
        EntityManagerInterface $em
    ) {
        
    }
}
