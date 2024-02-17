<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $numClient = null;

    #[ORM\Column(length: 255)]
    private ?string $nameClient = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseClient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumClient(): ?string
    {
        return $this->numClient;
    }

    public function setNumClient(string $numClient): static
    {
        $this->numClient = $numClient;

        return $this;
    }

    public function getNameClient(): ?string
    {
        return $this->nameClient;
    }

    public function setNameClient(string $nameClient): static
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    public function getAdresseClient(): ?string
    {
        return $this->adresseClient;
    }

    public function setAdresseClient(string $adresseClient): static
    {
        $this->adresseClient = $adresseClient;

        return $this;
    }
}
