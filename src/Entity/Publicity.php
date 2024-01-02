<?php

namespace App\Entity;

use App\Repository\PublicityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicityRepository::class)]
class Publicity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sentence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /*
    #[ORM\OneToMany(mappedBy: 'publicity', targetEntity: Report::class)]
    private Collection $Report;*/

    /*
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Audience $Audience = null;*/

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Stock $Stock = null;

    #[ORM\ManyToOne(inversedBy: 'Publicity')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'publicities')]
    private ?Guideline $Guideline = null;

    #[ORM\ManyToOne(inversedBy: 'publicities')]
    private ?Audience $Audience = null;

    #[ORM\ManyToOne(inversedBy: 'publicities')]
    private ?Show $Show = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $DeletedAt = null;

    public function __construct()
    {
        //$this->Report = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSentence(): ?string
    {
        return $this->sentence;
    }

    public function setSentence(string $sentence): static
    {
        $this->sentence = $sentence;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /*
     * @return Collection<int, Report>
     */
    /*
    public function getReport(): Collection
    {
        return $this->Report;
    }*/

    /*
    public function addReport(Report $report): static
    {
        if (!$this->Report->contains($report)) {
            $this->Report->add($report);
            $report->setPublicity($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->Report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPublicity() === $this) {
                $report->setPublicity(null);
            }
        }

        return $this;
    }*/

    /*
    public function getAudience(): ?Audience
    {
        return $this->Audience;
    }

    public function setAudience(?Audience $Audience): static
    {
        $this->Audience = $Audience;

        return $this;
    }*/

    public function getStock(): ?Stock
    {
        return $this->Stock;
    }

    public function setStock(?Stock $Stock): static
    {
        $this->Stock = $Stock;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getSentence();
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

    public function getAudience(): ?Audience
    {
        return $this->Audience;
    }

    public function setAudience(?Audience $Audience): static
    {
        $this->Audience = $Audience;

        return $this;
    }

    public function getShow(): ?Show
    {
        return $this->Show;
    }

    public function setShow(?Show $Show): static
    {
        $this->Show = $Show;

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
