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
        $authorId = $request->request->get('author'); //haal de author op
        $isbn = $request->request->get('isbn');
        $author = $em->getRepository(Author::class)->find($authorId); //zet om in author_id


        $book = new Book(); //maak object aan
        $book->setTitle($title);    //vul het
        $book->setAuthor($author);  //object met
        $book->setIsbn($isbn);      //de relevante waarden

        $em->persist($book);
        $em->flush(); //en doorspoelen maar

        return $this->redirectToRoute('app_book_list');

}
    #[Route('/book/list', name: 'app_book_list')]
    public function list(EntityManagerInterface $em): Response {
        $books = $em->getRepository(Book::class)->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/update/{id}', name: 'app_book_update')]
    public function update(Request $request, EntityManagerInterface $em, $id): Response
    {
        $book = $em->getRepository(Book::class)->find($id);
        $authors = $em->getRepository(Author::class)->findAll(); //haal alle auteurs op

        if ($request->isMethod('post')) { //alleen als er wat gepost is willen we Doctrine gebruiken

            $title = $request->request->get('title');
            $authorId = $request->request->get('author'); //haal de author naam op net als bij aanmaken
            $isbn = $request->request->get('isbn');
            $author = $em->getRepository(Author::class)->find($authorId); //Zet om in ID net als bij het aanmaken

            $book->setTitle($title);
            $book->setAuthor($author);// Lekker met ID en niet met naam
            $book->setIsbn($isbn);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_book_list');

        } else {
            return $this->render('book/update.html.twig', [ //als dat niet zo is moet twig een array terugkrijgen.
                'book' => $book,
                'authors' => $authors, //pass een array terug aan index voor de dropdown net als bij het aanmaken.
            ]);
        }

    }



    #[Route('/book/delete/{id}', name: 'app_book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response {

        $book = $em->getRepository(Book::class)->find($book->getId());

        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('app_book_list');

    }


}
