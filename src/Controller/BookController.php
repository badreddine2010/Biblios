<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="app_book")
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            // 'books'=>$books
        ]);
    }

    /**
     * @Route("/book/new",name="book-new")
     * @Route("/book/edit/{id}",name="book-edit")
     */
    public function addOrUpdatebook(
        Book $book = null,
        Request $req,
        EntityManagerInterface $em
    ) {
       
        if (!$book) {
            $book = new book();
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_book', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'mode' => $book->getId() != null,
        ]);
    }
    /**
     * @Route("/book/delete/{id}",name="book-delete")
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        // if (!$this->isGranted('ROLE_ADMIN')) {
		// 	return $this->render('/error/accessDenied.html.twig');
		// }
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);
        
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_book', [
            'id' => $book->getId()
        ]);
    }
}
