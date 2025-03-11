<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Videos>
     */
    #[ORM\OneToMany(targetEntity: Videos::class, mappedBy: 'cours')]
    private Collection $videos;

    /**
     * @var Collection<int, Progression>
     */
    #[ORM\OneToMany(targetEntity: Progression::class, mappedBy: 'cours')]
    private Collection $progressions;

    /**
     * @var Collection<int, RoadmapCours>
     */
    #[ORM\OneToMany(targetEntity: RoadmapCours::class, mappedBy: 'cours')]
    private Collection $roadmapCours;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->progressions = new ArrayCollection();
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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setCours($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getCours() === $this) {
                $video->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Progression>
     */
    public function getProgressions(): Collection
    {
        return $this->progressions;
    }

    public function addProgression(Progression $progression): static
    {
        if (!$this->progressions->contains($progression)) {
            $this->progressions->add($progression);
            $progression->setCours($this);
        }

        return $this;
    }

    public function removeProgression(Progression $progression): static
    {
        if ($this->progressions->removeElement($progression)) {
            // set the owning side to null (unless already changed)
            if ($progression->getCours() === $this) {
                $progression->setCours(null);
            }
        }

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
            $roadmapCour->setCours($this);
        }

        return $this;
    }

    public function removeRoadmapCour(RoadmapCours $roadmapCour): static
    {
        if ($this->roadmapCours->removeElement($roadmapCour)) {
            // set the owning side to null (unless already changed)
            if ($roadmapCour->getCours() === $this) {
                $roadmapCour->setCours(null);
            }
        }

        return $this;
    }




}
