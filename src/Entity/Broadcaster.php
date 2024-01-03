<?php

namespace App\Entity;

use App\Repository\BroadcasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcasterRepository::class)]
class Broadcaster extends Person
{
    #[ORM\OneToMany(mappedBy: 'broadcaster', targetEntity: Guideline::class)]
    private Collection $Guideline;

    #[ORM\Column(nullable: true)]
    private ?int $rut = null;

    #[ORM\Column(nullable: true, length: 1)]
    private ?string $dv = null;

    public function __construct()
    {
        $this->Guideline = new ArrayCollection();
    }

    /**
     * @return Collection<int, Guideline>
     */
    public function getGuideline(): Collection
    {
        return $this->Guideline;
    }

    public function getNonDeletedGuidelines() : Collection {
        $nonDeleted = new ArrayCollection();

        foreach($this->getGuideline() as $guideline){
            if($guideline->getDeletedAt() === null){
                $nonDeleted->add($guideline);
            }
        }

        return $nonDeleted;
    }

    public function addGuideline(Guideline $guideline): static
    {
        if (!$this->Guideline->contains($guideline)) {
            $this->Guideline->add($guideline);
            $guideline->setBroadcaster($this);
        }

        return $this;
    }

    public function removeGuideline(Guideline $guideline): static
    {
        if ($this->Guideline->removeElement($guideline)) {
            // set the owning side to null (unless already changed)
            if ($guideline->getBroadcaster() === $this) {
                $guideline->setBroadcaster(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
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
}

