<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LoanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
#[ApiResource]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'loans')] //uitleningen moeten weg als klant wordt verwijderd
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'loans')] //uitleningen moeten verwijderd worden als het boek wordt opgeruimd
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Book $book = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $loandate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getLoandate(): ?\DateTimeInterface
    {
        return $this->loandate;
    }

    public function setLoandate(\DateTimeInterface $loandate): static
    {
        $this->loandate = $loandate;

        return $this;
    }
}
