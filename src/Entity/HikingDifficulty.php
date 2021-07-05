<?php

namespace App\Entity;

use App\Repository\HikingDifficultyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HikingDifficultyRepository::class)
 */
class HikingDifficulty
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Hiking::class, mappedBy="difficulty", orphanRemoval=true)
     */
    private $hikings;

    public function __construct()
    {
        $this->hikings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Hiking[]
     */
    public function getHikings(): Collection
    {
        return $this->hikings;
    }

    public function addHiking(Hiking $hiking): self
    {
        if (!$this->hikings->contains($hiking)) {
            $this->hikings[] = $hiking;
            $hiking->setDifficulty($this);
        }

        return $this;
    }

    public function removeHiking(Hiking $hiking): self
    {
        if ($this->hikings->removeElement($hiking)) {
            // set the owning side to null (unless already changed)
            if ($hiking->getDifficulty() === $this) {
                $hiking->setDifficulty(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
