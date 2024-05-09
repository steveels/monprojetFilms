<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    /**
     * @var Collection<int, Films>
     */
    #[ORM\OneToMany(targetEntity: Films::class, mappedBy: 'filmscateg', orphanRemoval: true)]
    private Collection $filmscateg;

    public function __construct()
    {
        $this->filmscateg = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Films>
     */
    public function getFilmscateg(): Collection
    {
        return $this->filmscateg;
    }

    public function addFilmscateg(Films $filmscateg): static
    {
        if (!$this->filmscateg->contains($filmscateg)) {
            $this->filmscateg->add($filmscateg);
            $filmscateg->setFilmscateg($this);
        }

        return $this;
    }

    public function removeFilmscateg(Films $filmscateg): static
    {
        if ($this->filmscateg->removeElement($filmscateg)) {
            // set the owning side to null (unless already changed)
            if ($filmscateg->getFilmscateg() === $this) {
                $filmscateg->setFilmscateg(null);
            }
        }

        return $this;
    }
}
