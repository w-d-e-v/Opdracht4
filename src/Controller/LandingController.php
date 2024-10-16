<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LandingController extends AbstractController {
    #[Route('/', name: 'Landing Page')]
    public function index(): Response {
        return $this->render('index.html.twig', [
            'controller_name' => 'LandingController',
        ]);

}}