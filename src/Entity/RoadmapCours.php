<?php

namespace App\Entity;

use App\Repository\RoadmapCoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoadmapCoursRepository::class)]
class RoadmapCours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ord = null;

    #[ORM\ManyToOne(inversedBy: 'roadmapCours')]
    private ?Roadmaps $roadmap = null;

    #[ORM\ManyToOne(inversedBy: 'roadmapCours')]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrd(): ?int
    {
        return $this->ord;
    }

    public function setOrd(int $ord): static
    {
        $this->ord = $ord;

        return $this;
    }

    public function getRoadmap(): ?Roadmaps
    {
        return $this->roadmap;
    }

    public function setRoadmap(?Roadmaps $roadmap): static
    {
        $this->roadmap = $roadmap;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }
}
