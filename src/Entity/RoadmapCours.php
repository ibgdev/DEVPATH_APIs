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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?roadmaps $roadmap_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?cours $cours_id = null;

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

    public function getRoadmapId(): ?roadmaps
    {
        return $this->roadmap_id;
    }

    public function setRoadmapId(?roadmaps $roadmap_id): static
    {
        $this->roadmap_id = $roadmap_id;

        return $this;
    }

    public function getCoursId(): ?cours
    {
        return $this->cours_id;
    }

    public function setCoursId(?cours $cours_id): static
    {
        $this->cours_id = $cours_id;

        return $this;
    }
}
