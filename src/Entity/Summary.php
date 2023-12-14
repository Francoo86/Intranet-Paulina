<?php

namespace App\Entity;

use App\Repository\SummaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SummaryRepository::class)]
class Summary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'Summary', cascade: ['persist', 'remove'])]
    private ?Notification $notification = null;

    #[ORM\OneToOne(inversedBy: 'summary', cascade: ['persist', 'remove'])]
    private ?Notification $Notification = null;

    #[ORM\ManyToOne(inversedBy: 'Summary')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'summaries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $Customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        // unset the owning side of the relation if necessary
        if ($notification === null && $this->notification !== null) {
            $this->notification->setSummary(null);
        }

        // set the owning side of the relation if necessary
        if ($notification !== null && $notification->getSummary() !== $this) {
            $notification->setSummary($this);
        }

        $this->notification = $notification;

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
}
