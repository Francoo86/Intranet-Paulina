<?php

namespace App\Entity;

use App\Repository\ShowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowRepository::class)]
class Show
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $finish = null;

    #[ORM\OneToMany(mappedBy: 'Show', targetEntity: Publicity::class)]
    private Collection $publicities;

    #[ORM\ManyToOne(inversedBy: 'shows', fetch:"EAGER")]
    private ?Guideline $Guideline = null;

    public function __construct()
    {
        $this->publicities = new ArrayCollection();
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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): ?\DateTimeInterface
    {
        return $this->finish;
    }

    public function setFinish(\DateTimeInterface $finish): static
    {
        $this->finish = $finish;

        return $this;
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
            $publicity->setShow($this);
        }

        return $this;
    }

    public function removePublicity(Publicity $publicity): static
    {
        if ($this->publicities->removeElement($publicity)) {
            // set the owning side to null (unless already changed)
            if ($publicity->getShow() === $this) {
                $publicity->setShow(null);
            }
        }

        return $this;
    }

    public function getGuideline(): ?Guideline
    {
        return $this->Guideline;
    }

    public function setGuideline(?Guideline $Guideline): static
    {
        $this->Guideline = $Guideline;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
