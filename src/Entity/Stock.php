<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\OneToOne(inversedBy: 'Stock', cascade: ['persist', 'remove'])]
    private ?Publicity $Publicity = null;

    #[ORM\OneToOne(mappedBy: 'Stock', cascade: ['persist', 'remove'])]
    private ?Balance $balance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPublicity(): ?Publicity
    {
        return $this->Publicity;
    }

    public function setPublicity(?Publicity $Publicity): static
    {
        $this->Publicity = $Publicity;

        return $this;
    }

    public function getBalance(): ?Balance
    {
        return $this->balance;
    }

    public function setBalance(?Balance $balance): static
    {
        // unset the owning side of the relation if necessary
        if ($balance === null && $this->balance !== null) {
            $this->balance->setStock(null);
        }

        // set the owning side of the relation if necessary
        if ($balance !== null && $balance->getStock() !== $this) {
            $balance->setStock($this);
        }

        $this->balance = $balance;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}
