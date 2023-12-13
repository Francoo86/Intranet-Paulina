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
    private Collection $Publicities;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Payment::class)]
    private Collection $Payments;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Notification::class)]
    private Collection $Notifications;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Summary::class)]
    private Collection $Summarys;

    public function __construct()
    {
        $this->Publicities = new ArrayCollection();
        $this->Payments = new ArrayCollection();
        $this->Notifications = new ArrayCollection();
        $this->Summarys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
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
    public function getPublicities(): Collection
    {
        return $this->Publicities;
    }

    public function addPublicity(Publicity $publicity): static
    {
        if (!$this->Publicities->contains($publicity)) {
            $this->Publicities->add($publicity);
            $publicity->setCustomer($this);
        }

        return $this;
    }

    public function removePublicity(Publicity $publicity): static
    {
        if ($this->Publicities->removeElement($publicity)) {
            // set the owning side to null (unless already changed)
            if ($publicity->getCustomer() === $this) {
                $publicity->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->Payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->Payments->contains($payment)) {
            $this->Payments->add($payment);
            $payment->setCustomer($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->Payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getCustomer() === $this) {
                $payment->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->Notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->Notifications->contains($notification)) {
            $this->Notifications->add($notification);
            $notification->setCustomer($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->Notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getCustomer() === $this) {
                $notification->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Summary>
     */
    public function getSummarys(): Collection
    {
        return $this->Summarys;
    }

    public function addSummary(Summary $summary): static
    {
        if (!$this->Summarys->contains($summary)) {
            $this->Summarys->add($summary);
            $summary->setCustomer($this);
        }

        return $this;
    }

    public function removeSummary(Summary $summary): static
    {
        if ($this->Summarys->removeElement($summary)) {
            // set the owning side to null (unless already changed)
            if ($summary->getCustomer() === $this) {
                $summary->setCustomer(null);
            }
        }

        return $this;
    }
}
