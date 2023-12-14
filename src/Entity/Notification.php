<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToOne(mappedBy: 'Notification', cascade: ['persist', 'remove'])]
    private ?Balance $balance = null;

    #[ORM\OneToOne(inversedBy: 'notification', cascade: ['persist', 'remove'])]
    private ?Balance $Balance = null;

    #[ORM\ManyToOne(inversedBy: 'Notification')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $Customer = null;

    #[ORM\OneToOne(inversedBy: 'notification', cascade: ['persist', 'remove'])]
    private ?Summary $Summary = null;

    #[ORM\OneToOne(mappedBy: 'Notification', cascade: ['persist', 'remove'])]
    private ?Summary $summary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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
            $this->balance->setNotification(null);
        }

        // set the owning side of the relation if necessary
        if ($balance !== null && $balance->getNotification() !== $this) {
            $balance->setNotification($this);
        }

        $this->balance = $balance;

        return $this;
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

    public function getSummary(): ?Summary
    {
        return $this->Summary;
    }

    public function setSummary(?Summary $Summary): static
    {
        $this->Summary = $Summary;

        return $this;
    }
}
