<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager extends Person
{
    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: Guideline::class)]
    private Collection $Guideline;

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

    public function addGuideline(Guideline $guideline): static
    {
        if (!$this->Guideline->contains($guideline)) {
            $this->Guideline->add($guideline);
            $guideline->setManager($this);
        }

        return $this;
    }

    public function removeGuideline(Guideline $guideline): static
    {
        if ($this->Guideline->removeElement($guideline)) {
            // set the owning side to null (unless already changed)
            if ($guideline->getManager() === $this) {
                $guideline->setManager(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
