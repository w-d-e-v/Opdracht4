<?php

namespace App\Controller;

use App\Entity\Author;
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
    public function index(EntityManagerInterface $em): Response
    {
        $authors = $em->getRepository(Author::class)->findAll(); //haal alle auteurs op
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'authors' => $authors, //pass een array terug aan index voor de dropdown!
        ]);
    }

    #[Route('/book/create', name: 'app_book_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
{


        //haal de waarden an het webformulier uit de post
        $title = $request->request->get('title');
        $authorId = $request->request->get('author'); //haal het id op
        $isbn = $request->request->get('isbn');
        $author = $em->getRepository(Author::class)->find($authorId); //zet om in author_id


        $book = new Book(); //maak object aan
        $book->setTitle($title);    //vul het
        $book->setAuthor($author);  //object met
        $book->setIsbn($isbn);      //de relevante waarden

        $em->persist($book);
        $em->flush(); //en doorspoelen maar

        return new Response("Book " . $book->getTitle() . " is aangemaakt");

}
    #[Route('/book/list', name: 'app_book_list')]
    public function list(EntityManagerInterface $em): Response {
        $books = $em->getRepository(Book::class)->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/delete/{id}', name: 'app_book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response {

        $book = $em->getRepository(Book::class)->find($book->getId());

        $em->remove($book);
        $em->flush();
        return new Response("Boek " . $book->getTitle() . " is verwijderd");

    }


}
