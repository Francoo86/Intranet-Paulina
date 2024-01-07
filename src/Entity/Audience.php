<?php

namespace App\Entity;

use App\Repository\AudienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AudienceRepository::class)]
class Audience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $demography = null;

    #[ORM\Column(length: 255)]
    private ?string $locality = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'Audience', targetEntity: Publicity::class)]
    private Collection $publicities;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $DeletedAt = null;

    public function __construct()
    {
        $this->publicities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemography(): ?string
    {
        return $this->demography;
    }

    public function setDemography(string $demography): static
    {
        $this->demography = $demography;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(string $locality): static
    {
        $this->locality = $locality;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getDemography();
    }

    /**
     * @return Collection<int, Publicity>
     */
    public function getPublicities(): Collection
    {
        return $this->publicities;
    }

    public function addPublicity(Publicity $publicity): static
    {
        if (!$this->publicities->contains($publicity)) {
            $this->publicities->add($publicity);
            $publicity->setAudience($this);
        }

        return $this;
    }

    public function removePublicity(Publicity $publicity): static
    {
        if ($this->publicities->removeElement($publicity)) {
            // set the owning side to null (unless already changed)
            if ($publicity->getAudience() === $this) {
                $publicity->setAudience(null);
            }
        }

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
