<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Int_;

#[ORM\Entity(repositoryClass: BandRepository::class)]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $groupName = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'band', cascade: ['persist'])]
    private Country $country;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'band', cascade: ['persist'])]
    private City $city;

    #[ORM\OneToMany(targetEntity: Founder::class, mappedBy: 'band')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Collection $founder;

    #[ORM\ManyToOne(targetEntity: MusicType::class, inversedBy: 'band', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?MusicType $musicType;

    #[ORM\Column(length: 255)]
    private ?int $beginningYears = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $endingYears = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $members = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->founder = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): string {
        return $this->groupName;
    }

    public function setGrouName(string $groupName): self {
        $this->groupName = $groupName;

        return $this;
    }

    public function getCountry(): Country {
        return $this->country;
    }

    public function setCountry(Country $country): self {
        $this->country = $country;

        return $this;
    }

    public function getCity(): City {
        return $this->city;
    }

    public function setCity(City $city): self {
        $this->city = $city;

        return $this;
    }

    public function getFounder(): Collection {
        return $this->founder;
    }

    public function setFounder(?Founder $founder): self {
        $this->founder = $founder;

        return $this;
    }

    public function getMusicType(): ?MusicType {
        return $this->musicType;
    }

    public function setMusicType(?MusicType $musicType): self {
        $this->musicType = $musicType;

        return $this;
    }

    public function getBeginningYears(): ?int {
        return $this->beginningYears;
    }

    public function setBeginningYears(int $beginningYears): self {
        $this->beginningYears = $beginningYears;

        return $this;
    }

    public function getEndingYears(): ?int {
        return $this->endingYears;
    }

    public function setEndingYears(?int $endingYears): self {
        $this->endingYears = $endingYears;

        return $this;
    }

    public function getMembers(): ?int {
        return $this->members;
    }

    public function setMembers(?int $members): self {
        $this->members = $members;

        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

}
