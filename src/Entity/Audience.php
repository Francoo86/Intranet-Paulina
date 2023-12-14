<?php

namespace App\Entity;

use App\Repository\AudienceRepository;
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

    #[ORM\Column]
    private array $interests = [];

    #[ORM\Column(length: 255)]
    private ?string $locality = null;

    #[ORM\OneToOne(mappedBy: 'Audience', cascade: ['persist', 'remove'])]
    private ?Publicity $publicity = null;

    #[ORM\OneToOne(inversedBy: 'audience', cascade: ['persist', 'remove'])]
    private ?Publicity $Publicity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemography(): ?string
    {
        return $this->demography;
    }

    public function setDemography(?string $demography): static
    {
        $this->demography = $demography;

        return $this;
    }

    public function getInterests(): array
    {
        return $this->interests;
    }

    public function setInterests(array $interests): static
    {
        $this->interests = $interests;

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

    public function getPublicity(): ?Publicity
    {
        return $this->publicity;
    }

    public function setPublicity(Publicity $publicity): static
    {
        // set the owning side of the relation if necessary
        if ($publicity->getAudience() !== $this) {
            $publicity->setAudience($this);
        }

        $this->publicity = $publicity;

        return $this;
    }
}
