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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: Band::class, mappedBy: 'musicType', cascade: ['persist'])]
    private Collection $band;

    public function __construct()
    {
        $this->band = new ArrayCollection();
    }

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(?string $type): self {
        $this->type = $type;

        return $this;
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
