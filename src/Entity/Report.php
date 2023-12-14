<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'Reports')]
    private ?Manager $manager = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?Manager $Manager = null;

    #[ORM\ManyToOne(inversedBy: 'Reports')]
    private ?Publicity $publicity = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?Publicity $Publicity = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getPublicity(): ?Publicity
    {
        return $this->publicity;
    }

    public function setPublicity(?Publicity $publicity): static
    {
        $this->publicity = $publicity;

        return $this;
    }
}
