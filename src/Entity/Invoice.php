<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?User $user_id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $date_de_transaction = null;

    #[ORM\Column(type: 'float')]
    private ?float $montant = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $ville_de_facturation = null;

    #[ORM\Column(type: 'string', length: 5)]
    private ?string $zipcode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDateDeTransaction(): ?\DateTimeImmutable
    {
        return $this->date_de_transaction;
    }

    public function setDateDeTransaction(\DateTimeImmutable $date_de_transaction): static
    {
        $this->date_de_transaction = $date_de_transaction;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getVilleDeFacturation(): ?string
    {
        return $this->ville_de_facturation;
    }

    public function setVilleDeFacturation(string $ville_de_facturation): static
    {
        $this->ville_de_facturation = $ville_de_facturation;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }
}
