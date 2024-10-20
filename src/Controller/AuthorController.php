<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/create', name: 'app_author_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        //haal de waarde 'name' an het webformulier uit de post
        $name = $request->request->get('name');

        $author = new Author();
        $author->setName($name); //geen dubbele quotes gebruiken rond een variabele 🤡

        $em->persist($author);
        $em->flush();  //doorspoelen maar

        return new Response("Author " . $author->getName() . " is aangemaakt");

    }

    #[Route('/author/list', name: 'app_author_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $authors = $em->getRepository(Author::class)->findAll(); //lees alles uit
        return $this->render('author/list.html.twig', [
            'authors' => $authors, //pass een array terug
        ]);
    }

    #[Route('/author/update/{id}', name: 'app_author_update')]
    public function update(Request $request, EntityManagerInterface $em, $id): Response
    {
        $author = $em->getRepository(Author::class)->find($id);


        if ($request->isMethod('post')) { //alleen als er wat gepost is willen we Doctrine gebruiken

            $name = $request->request->get('name');
            $author->setName($name);

            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('app_author_list');

        } else {
            return $this->render('author/update.html.twig', [ //als dat niet zo is moet twig een array terugkrijgen.
                'author' => $author,
            ]);
        }

    }








    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function delete(Author $author, EntityManagerInterface $em): Response {

        $author = $em->getRepository(Author::class)->find($author->getId());

        $em->remove($author);
        $em->flush();
        return new Response("Author " . $author->getName() . " is verwijderd");

    }

}
