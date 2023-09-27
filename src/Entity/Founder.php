<?php

namespace App\Entity;

use App\Repository\FounderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FounderRepository::class)]
class Founder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Band::class, inversedBy: 'founder', cascade: ['persist'])]
    private Band $band;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): self
    {
        $this->band = $band;

        return $this;
    }
}
