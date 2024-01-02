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

    #[ORM\Column(type: 'boolean')]
    private ?bool $active = null;

    #[ORM\OneToOne(inversedBy: 'Balance', cascade: ['persist', 'remove'])]
    private ?Stock $Stock = null; 

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
        return $this->Stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->Stock = $stock;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        // unset the owning side of the relation if necessary
        if ($notification === null && $this->notification !== null) {
            $this->notification->setBalance(null);
        }

        // set the owning side of the relation if necessary
        if ($notification !== null && $notification->getBalance() !== $this) {
            $notification->setBalance($this);
        }

        $this->notification = $notification;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}
