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

    #[ORM\OneToOne(mappedBy: 'Stocks', cascade: ['persist', 'remove'])]
    private ?Publicity $publicity = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Balance $Balances = null;

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
        return $this->publicity;
    }

    public function setPublicity(Publicity $publicity): static
    {
        // set the owning side of the relation if necessary
        if ($publicity->getStocks() !== $this) {
            $publicity->setStocks($this);
        }

        $this->publicity = $publicity;

        return $this;
    }

    public function getBalances(): ?Balance
    {
        return $this->Balances;
    }

    public function setBalances(Balance $Balances): static
    {
        $this->Balances = $Balances;

        return $this;
    }
}
