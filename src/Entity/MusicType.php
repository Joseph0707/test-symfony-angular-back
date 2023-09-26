<?php

namespace App\Entity;

use App\Repository\MusicTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicTypeRepository::class)]
class MusicType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Band::class, mappedBy: 'musicType')]
    private Collection $band;

    public function __construct()
    {
        $this->band = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBand(): Collection {
        return $this->band;
    }

    public function setBand(Band $band): self {
        $this->band = $band;
        
        return $this;
    }
}
