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

    #[ORM\OneToMany(mappedBy: 'publicity', targetEntity: Report::class)]
    private Collection $Reports;

    #[ORM\OneToOne(inversedBy: 'publicity', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Audience $Audience = null;

    #[ORM\OneToOne(inversedBy: 'publicity', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stock $Stocks = null;

    #[ORM\ManyToOne(inversedBy: 'Publicities')]
    private ?Customer $customer = null;

    public function __construct()
    {
        $this->Reports = new ArrayCollection();
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
            $report->setPublicity($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->Reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPublicity() === $this) {
                $report->setPublicity(null);
            }
        }

        return $this;
    }

    public function getAudience(): ?Audience
    {
        return $this->Audience;
    }

    public function setAudience(Audience $Audience): static
    {
        $this->Audience = $Audience;

        return $this;
    }

    public function getStocks(): ?Stock
    {
        return $this->Stocks;
    }

    public function setStocks(Stock $Stocks): static
    {
        $this->Stocks = $Stocks;

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
}
