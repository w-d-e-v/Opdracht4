<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Book;
use App\Entity\Loan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoanController extends AbstractController
{
    #[Route('/loan', name: 'app_loan')]
    public function index(EntityManagerInterface $em): Response
    {
        $books = $em->getRepository(Book::class)->findAll(); //haal alle boeken op
        $customers = $em->getRepository(Customer::class)->findAll(); //haal alle klanten op
        return $this->render('loan/index.html.twig', [
            'controller_name' => 'LoanController',
            'books' => $books,
            'customers' => $customers,
        ]);
    }

    #[Route('/loan/create', name: 'app_loan_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {


        //haal de waarden an het webformulier uit de post
        $customerId = $request->request->get('customer'); //haal het id op
        $bookId = $request->request->get('book'); //haal het id op
        $customer = $em->getRepository(Customer::class)->find($customerId); //zet om in author_id
        $book = $em->getRepository(Book::class)->find($bookId); //ook als bovenstaand

        $loanDate = new \DateTime(); //klats hier een nieuw object met timestamp in want een formatted date() string wordt niet gevreten

        $loan = new Loan(); //maak object aan
        $loan->setCustomer($customer);    //vul het
        $loan->setBook($book);  //object met
        $loan->setLoandate($loanDate);      //de relevante waarden

        $em->persist($loan);
        $em->flush(); //en doorspoelen maar

        return $this->redirectToRoute('app_loan_list');

    }

    #[Route('/loan/list', name: 'app_loan_list')]
    public function list(EntityManagerInterface $em): Response {
        $loans = $em->getRepository(Loan::class)->findAll(); //lees alles uit

        foreach ($loans as $loan) { //stom datetime object moet geconverteerd worden naar een string
            $loan->stringedDate = $loan->getLoandate()->format('d-m-Y H:i:s');
        }

        return $this->render('loan/list.html.twig', [
            'loans' => $loans, //pass een array terug
        ]);
    }

    #[Route('/loan/update/{id}', name: 'app_loan_update')]
    public function update(Request $request, EntityManagerInterface $em, $id): Response
    {
        $loan = $em->getRepository(Loan::class)->find($id); //pak het loan object voor initiatie
        $books = $em->getRepository(Book::class)->findAll(); //Haal hier ALLE boeken op
        $customers = $em->getRepository(Customer::class)->findAll(); //haal alle auteurs op

        if ($request->isMethod('post')) { //alleen als er wat gepost is willen we Doctrine gebruiken

            $bookId = $request->request->get('book'); //haal boektitel uit het formulier
            $customerId = $request->request->get('customer'); //haal de customer naam op net als bij aanmaken

            $book = $em->getRepository(Book::class)->find($bookId); //Zet om in ID net als bij het aanmaken
            $customer = $em->getRepository(Customer::class)->find($customerId); //Dat doen weo ook voor de klant


            $loan->setBook($book); //ook hier het ID gebruiken
            $loan->setCustomer($customer);// Lekker met ID en niet met naam


            $em->persist($loan);
            $em->flush();

            return $this->redirectToRoute('app_loan_list');

        } else {
            return $this->render('loan/update.html.twig', [ //als dat niet zo is moet twig een array terugkrijgen.
                'loan' => $loan, //retourneer de informatie over deze uitlening in array
                'books' => $books, //array met boeken terug naar de pagina
                'customers' => $customers, //pass een array terug aan index voor de dropdown net als bij het aanmaken.
            ]);
        }

    }


    #[Route('/loan/delete/{id}', name: 'app_loan_delete')]
    public function delete(Loan $loan, EntityManagerInterface $em): Response {

        $loan = $em->getRepository(Loan::class)->find($loan->getId());

        $em->remove($loan);
        $em->flush();
        return $this->redirectToRoute('app_loan_list');

    }



}
