<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FilmsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FilmsRepository::class)]
class Films
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupe1'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['groupe1'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['groupe1'])]
    private ?\DateTimeInterface $date_de_sortie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['groupe1'])]
    private ?string $content = null;


    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'films', orphanRemoval: true)]
    private Collection $commentaires;

    /**
     * @var Collection<int, Likes>
     */
    #[ORM\OneToMany(targetEntity: Likes::class, mappedBy: 'filmslikes', orphanRemoval: true)]
    private Collection $likesfilm;

    #[ORM\ManyToOne(inversedBy: 'filmscateg')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $filmscateg = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['groupe1'])]
    private ?string $images = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likesfilm = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTimeInterface
    {
        return $this->date_de_sortie;
    }

    public function setDateDeSortie(?\DateTimeInterface $date_de_sortie): static
    {
        $this->date_de_sortie = $date_de_sortie;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
   
    
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setFilms($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFilms() === $this) {
                $commentaire->setFilms(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Likes>
     */
    public function getLikesfilm(): Collection
    {
        return $this->likesfilm;
    }

    public function addLikesfilm(Likes $likesfilm): static
    {
        if (!$this->likesfilm->contains($likesfilm)) {
            $this->likesfilm->add($likesfilm);
            $likesfilm->setFilmslikes($this);
        }

        return $this;
    }

    public function removeLikesfilm(Likes $likesfilm): static
    {
        if ($this->likesfilm->removeElement($likesfilm)) {
            // set the owning side to null (unless already changed)
            if ($likesfilm->getFilmslikes() === $this) {
                $likesfilm->setFilmslikes(null);
            }
        }

        return $this;
    }

    public function getFilmscateg(): ?Categories
    {
        return $this->filmscateg;
    }

    public function setFilmscateg(?Categories $filmscateg): static
    {
        $this->filmscateg = $filmscateg;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): static
    {
        $this->images = $images;

        return $this;
    }
}
