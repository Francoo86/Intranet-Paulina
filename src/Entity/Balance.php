<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalanceRepository::class)]
class Balance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToOne(mappedBy: 'Balance', cascade: ['persist', 'remove'])]
    private ?Stock $stock = null;

    #[ORM\OneToOne(inversedBy: 'balance', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stock $Stock = null;

    #[ORM\OneToOne(inversedBy: 'balance', cascade: ['persist', 'remove'])]
    private ?Notification $Notification = null;

    #[ORM\OneToOne(mappedBy: 'Balance', cascade: ['persist', 'remove'])]
    private ?Notification $notification = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): static
    {
        // set the owning side of the relation if necessary
        if ($stock->getBalance() !== $this) {
            $stock->setBalance($this);
        }

        $this->stock = $stock;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->Notification;
    }

    public function setNotification(?Notification $Notification): static
    {
        $this->Notification = $Notification;

        return $this;
    }
}