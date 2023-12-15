<?php

namespace App\Entity;

use App\Repository\BroadcasterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcasterRepository::class)]
class Broadcaster extends Person
{

}
