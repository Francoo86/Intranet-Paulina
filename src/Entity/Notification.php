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

    #[ORM\ManyToOne(inversedBy: 'Notifications')]
    private ?Customer $customer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Balance $Balance = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Summary $Summarys = null;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getBalance(): ?Balance
    {
        return $this->Balance;
    }

    public function setBalance(?Balance $Balance): static
    {
        $this->Balance = $Balance;

        return $this;
    }

    public function getSummarys(): ?Summary
    {
        return $this->Summarys;
    }

    public function setSummarys(?Summary $Summarys): static
    {
        $this->Summarys = $Summarys;

        return $this;
    }
}
