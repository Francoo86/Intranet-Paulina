<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: Guideline::class)]
    private Collection $Guideline;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: Report::class)]
    private Collection $Report;

    public function __construct()
    {
        $this->Guideline = new ArrayCollection();
        $this->Report = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Report>
     */
    public function getReport(): Collection
    {
        return $this->Report;
    }

    public function addReport(Report $report): static
    {
        if (!$this->Report->contains($report)) {
            $this->Report->add($report);
            $report->setManager($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->Report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getManager() === $this) {
                $report->setManager(null);
            }
        }

        return $this;
    }
}
