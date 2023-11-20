<?php

namespace App\Entity;

use App\Repository\BroadcasterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcasterRepository::class)]
class Broadcaster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
