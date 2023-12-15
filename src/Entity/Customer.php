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

    public function __construct()
    {
        $this->Publicity = new ArrayCollection();
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
}
