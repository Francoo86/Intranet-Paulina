<?php

namespace App\Entity;

use App\Repository\BroadcasterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcasterRepository::class)]
class Broadcaster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'broadcaster', targetEntity: Guideline::class)]
    private Collection $Guidelines;

    #[ORM\OneToMany(mappedBy: 'Broadcaster', targetEntity: Guideline::class)]
    private Collection $guidelines;

    public function __construct()
    {
        $this->Guidelines = new ArrayCollection();
        $this->guidelines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $guideline->setBroadcaster($this);
        }

        return $this;
    }

    public function removeGuideline(Guideline $guideline): static
    {
        if ($this->Guidelines->removeElement($guideline)) {
            // set the owning side to null (unless already changed)
            if ($guideline->getBroadcaster() === $this) {
                $guideline->setBroadcaster(null);
            }
        }

        return $this;
    }
}
