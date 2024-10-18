<?php

namespace App\Controller;

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
    public function create(EntityManagerInterface $em): Response
    {

        $author = new Author();
        $author->setName('Multatuli');

        $em->persist($author);
        $em->flush();

        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

}
