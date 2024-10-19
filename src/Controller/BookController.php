<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/create', name: 'app_book_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
{
    //haal de waarden an het webformulier uit de post
    $title = $request->request->get('title');
    $author = $request->request->get('author');
    $isbn = $request->request->get('isbn');

    $book = new Book();
    $book->setTitle($title); //geen dubbele quotes gebruiken rond een variabele 🤡
    $book->setAuthor($author);
    $book->setIsbn($isbn);

    $em->persist($book);
    $em->flush();

    return new Response("Book " . $book->getTitle() . " is aangemaakt");

}
    #[Route('/book/list', name: 'app_book_list')]
    public function list(EntityManagerInterface $em): Response {
        $books = $em->getRepository(Book::class)->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }



}
