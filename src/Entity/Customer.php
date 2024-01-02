<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organisation = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Publicity::class)]
    private Collection $Publicity;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Payment::class)]
    private Collection $Payment;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Summary::class)]
    private Collection $Summary;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Notification::class)]
    private Collection $Notification;

    #[ORM\Column]
    private ?int $rut = null;

    #[ORM\Column(length: 1)]
    private ?string $dv = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $DeletedAt = null;

    public function __construct()
    {
        $this->Publicity = new ArrayCollection();
        $this->Payment = new ArrayCollection();
        $this->Summary = new ArrayCollection();
        $this->Notification = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return ($this->getDeletedAt() === null ? $this->name : "CLIENTE ELIMINADO");
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOrganisation(): ?string
    {
        return $this->organisation;
    }

    public function setOrganisation(?string $organisation): static
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Publicity>
     */
    public function getPublicity(): Collection
    {
        return $this->Publicity;
    }

    public function addPublicity(Publicity $publicity): static
    {
        if (!$this->Publicity->contains($publicity)) {
            $this->Publicity->add($publicity);
            $publicity->setCustomer($this);
        }

        return $this;
    }

    public function removePublicity(Publicity $publicity): static
    {
        if ($this->Publicity->removeElement($publicity)) {
            // set the owning side to null (unless already changed)
            if ($publicity->getCustomer() === $this) {
                $publicity->setCustomer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() . " of " . $this->getOrganisation();
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayment(): Collection
    {
        return $this->Payment;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->Payment->contains($payment)) {
            $this->Payment->add($payment);
            $payment->setCustomer($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->Payment->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getCustomer() === $this) {
                $payment->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Summary>
     */
    public function getSummary(): Collection
    {
        return $this->Summary;
    }

    public function addSummary(Summary $summary): static
    {
        if (!$this->Summary->contains($summary)) {
            $this->Summary->add($summary);
            $summary->setCustomer($this);
        }

        return $this;
    }

    public function removeSummary(Summary $summary): static
    {
        if ($this->Summary->removeElement($summary)) {
            // set the owning side to null (unless already changed)
            if ($summary->getCustomer() === $this) {
                $summary->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotification(): Collection
    {
        return $this->Notification;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->Notification->contains($notification)) {
            $this->Notification->add($notification);
            $notification->setCustomer($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->Notification->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getCustomer() === $this) {
                $notification->setCustomer(null);
            }
        }

        return $this;
    }

    public function getRut(): ?int
    {
        return $this->rut;
    }

    public function setRut(int $rut): static
    {
        $this->rut = $rut;

        return $this;
    }

    public function getDv(): ?string
    {
        return $this->dv;
    }

    public function setDv(string $dv): static
    {
        $this->dv = $dv;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->DeletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $DeletedAt): static
    {
        $this->DeletedAt = $DeletedAt;

        return $this;
    }
}
