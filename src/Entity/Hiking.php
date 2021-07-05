<?php

namespace App\Entity;

use App\Repository\HikingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HikingRepository::class)
 */
class Hiking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $distance;

    /**
     * @ORM\Column(type="integer")
     */
    private $elevation_gain;

    /**
     * @ORM\Column(type="integer")
     */
    private $highest_point;

    /**
     * @ORM\Column(type="integer")
     */
    private $lowest_point;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $return_start_point;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $municipality;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $modified_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=HikingDifficulty::class, inversedBy="hikings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity=HikingType::class, inversedBy="hikings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=WayPoint::class, mappedBy="hiking", orphanRemoval=true)
     */
    private $wayPoints;

    public function __construct()
    {
        $this->wayPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getElevationGain(): ?int
    {
        return $this->elevation_gain;
    }

    public function setElevationGain(int $elevation_gain): self
    {
        $this->elevation_gain = $elevation_gain;

        return $this;
    }

    public function getHighestPoint(): ?int
    {
        return $this->highest_point;
    }

    public function setHighestPoint(int $highest_point): self
    {
        $this->highest_point = $highest_point;

        return $this;
    }

    public function getLowestPoint(): ?int
    {
        return $this->lowest_point;
    }

    public function setLowestPoint(int $lowest_point): self
    {
        $this->lowest_point = $lowest_point;

        return $this;
    }

    public function getReturnStartPoint(): ?bool
    {
        return $this->return_start_point;
    }

    public function setReturnStartPoint(?bool $return_start_point): self
    {
        $this->return_start_point = $return_start_point;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function setMunicipality(string $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeImmutable $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDifficulty(): ?HikingDifficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?HikingDifficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getType(): ?HikingType
    {
        return $this->type;
    }

    public function setType(?HikingType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|WayPoint[]
     */
    public function getWayPoints(): Collection
    {
        return $this->wayPoints;
    }

    public function addWayPoint(WayPoint $wayPoint): self
    {
        if (!$this->wayPoints->contains($wayPoint)) {
            $this->wayPoints[] = $wayPoint;
            $wayPoint->setHiking($this);
        }

        return $this;
    }

    public function removeWayPoint(WayPoint $wayPoint): self
    {
        if ($this->wayPoints->removeElement($wayPoint)) {
            // set the owning side to null (unless already changed)
            if ($wayPoint->getHiking() === $this) {
                $wayPoint->setHiking(null);
            }
        }

        return $this;
    }
}
