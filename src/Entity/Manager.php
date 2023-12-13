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
    private Collection $Guidelines;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: Report::class)]
    private Collection $Reports;

    public function __construct()
    {
        $this->Guidelines = new ArrayCollection();
        $this->Reports = new ArrayCollection();
    }

    /**
     * @return Collection<int, Guideline>
     */
    public function getGuidelines(): Collection
    {
        return $this->Guidelines;
    }

    public function addGuideline(Guideline $guideline): static
    {
        if (!$this->Guidelines->contains($guideline)) {
            $this->Guidelines->add($guideline);
            $guideline->setManager($this);
        }

        return $this;
    }

    public function removeGuideline(Guideline $guideline): static
    {
        if ($this->Guidelines->removeElement($guideline)) {
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
    public function getReports(): Collection
    {
        return $this->Reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->Reports->contains($report)) {
            $this->Reports->add($report);
            $report->setManager($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->Reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getManager() === $this) {
                $report->setManager(null);
            }
        }

        return $this;
    }
}
