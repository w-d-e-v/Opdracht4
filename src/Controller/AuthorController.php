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
        $em->flush();

        return new Response("Author " . $author->getName() . " is aangemaakt");

    }

}
