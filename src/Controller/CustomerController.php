<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/customer/create', name: 'app_customer_create')]
    public function create(Request $request, EntityManagerInterface $em): Response {

        //haal de waarden an het webformulier uit de post
        $name = $request->request->get('name');
        $address = $request->request->get('address');

        $customer = new Customer(); //maak object aan
        $customer->setName($name);    //vul het
        $customer->setAddress($address);  //object metd e relevante waarden

        $em->persist($customer);
        $em->flush(); //en doorspoelen maar

        return new Response("Klant " . $customer->getName() . " is aangemaakt");
    }

    #[Route('/customer/list', name: 'app_customer_list')]
    public function list(EntityManagerInterface $em): Response {
        $customers = $em->getRepository(Customer::class)->findAll(); //lees alles uit
        return $this->render('customer/list.html.twig', [
            'customers' => $customers, //pass een array terug
        ]);
    }

    #[Route('/customer/delete/{id}', name: 'app_customer_delete')]
    public function delete(Customer $customer, EntityManagerInterface $em): Response {

        $customer = $em->getRepository(Customer::class)->find($customer->getId());

        $em->remove($customer);
        $em->flush();
        return new Response("Klant " . $customer->getName() . " is verwijderd");

    }

}
