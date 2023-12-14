<?php

namespace App\Entity;

use App\Repository\GuidelineRepository;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShowName(): ?string
    {
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
}
