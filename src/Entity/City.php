<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\OneToMany(targetEntity: Band::class, mappedBy: 'city', cascade: ['persist'])]
    private Collection $band;
    
    public function __construct(
        #[ORM\Column(length: 255)]
        private ?string $name = null,        
    )
    {
        $this->band = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        
        return $this;
    }

    public function getBand(): Collection {
        return $this->band;
    }

    public function setBand(Band $band): self {
        $this->band = $band;
        
        return $this;
    }
}
