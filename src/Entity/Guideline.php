<?php

namespace App\Entity;

use App\Repository\GuidelineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuidelineRepository::class)]
class Guideline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $show_name = null;

    #[ORM\Column]
    private ?int $emission_number = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'Guideline')]
    private ?Broadcaster $broadcaster = null;

    #[ORM\ManyToOne(inversedBy: 'Guideline')]
    private ?Manager $manager = null;

    //#[ORM\OneToMany(mappedBy: 'Guideline', targetEntity: Publicity::class)]
    //private Collection $publicities;
    
    #[ORM\OneToMany(mappedBy: 'Guideline', targetEntity: Show::class, fetch:"EAGER")]
    private Collection $shows;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $DeletedAt = null;

    public function __construct()
    {
        //$this->publicities = new ArrayCollection();
        $this->shows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShowName(): ?string
    {
        if($this->getDeletedAt() !== null){
            return "PAUTA ELIMINADA";
        }

        return $this->show_name;
    }

    public function setShowName(string $show_name): static
    {
        $this->show_name = $show_name;

        return $this;
    }

    public function getEmissionNumber(): ?int
    {
        return $this->emission_number;
    }

    public function setEmissionNumber(int $emission_number): static
    {
        $this->emission_number = $emission_number;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getBroadcaster(): ?Broadcaster
    {
        return $this->broadcaster;
    }

    public function setBroadcaster(?Broadcaster $broadcaster): static
    {
        $this->broadcaster = $broadcaster;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Publicity>
     */
    /*
    public function getPublicities(): Collection
    {
        return $this->publicities;
    }

    public function addPublicity(Publicity $publicity): static
    {
        if (!$this->publicities->contains($publicity)) {
            $this->publicities->add($publicity);
            $publicity->setGuideline($this);
        }

        return $this;
    }

    public function removePublicity(Publicity $publicity): static
    {
        if ($this->publicities->removeElement($publicity)) {
            // set the owning side to null (unless already changed)
            if ($publicity->getGuideline() === $this) {
                $publicity->setGuideline(null);
            }
        }

        return $this;
    }*/

    public function __toString()
    {
        return $this->getShowName();
    }

    /**
     * @return Collection<int, Show>
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function getNonDeletedShows(): Collection
    {
        $nonDeleted = new ArrayCollection();

        foreach ($this->shows as $show) {
            if ($show->getDeletedAt() === null) {
                $nonDeleted->add($show);
            }
        }
    
        return $nonDeleted;
        //return $this->shows;
    }

    public function addShow(Show $show): static
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
            $show->setGuideline($this);
        }

        return $this;
    }

    public function removeShow(Show $show): static
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getGuideline() === $this) {
                $show->setGuideline(null);
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
