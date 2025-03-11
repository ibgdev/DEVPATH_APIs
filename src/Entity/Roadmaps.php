<?php

namespace App\Entity;

use App\Repository\RoadmapsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoadmapsRepository::class)]
class Roadmaps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, RoadmapCours>
     */
    #[ORM\OneToMany(targetEntity: RoadmapCours::class, mappedBy: 'roadmap')]
    private Collection $roadmapCours;

    public function __construct()
    {
        $this->roadmapCours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, RoadmapCours>
     */
    public function getRoadmapCours(): Collection
    {
        return $this->roadmapCours;
    }

    public function addRoadmapCour(RoadmapCours $roadmapCour): static
    {
        if (!$this->roadmapCours->contains($roadmapCour)) {
            $this->roadmapCours->add($roadmapCour);
            $roadmapCour->setRoadmap($this);
        }

        return $this;
    }

    public function removeRoadmapCour(RoadmapCours $roadmapCour): static
    {
        if ($this->roadmapCours->removeElement($roadmapCour)) {
            // set the owning side to null (unless already changed)
            if ($roadmapCour->getRoadmap() === $this) {
                $roadmapCour->setRoadmap(null);
            }
        }

        return $this;
    }
}
