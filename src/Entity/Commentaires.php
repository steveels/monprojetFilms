<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentairesRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     /**
     * @Groups({"comment"})
     */
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
     /**
     * @Groups({"comment"})
     */
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
     /**
     * @Groups({"comment"})
     */
    private ?\DateTimeInterface $date_commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Films $films = null;

    #[ORM\ManyToOne(inversedBy: 'usercomment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    /**
     * @var Collection<int, LikeCommentaire>
     */
    #[ORM\OneToMany(targetEntity: LikeCommentaire::class, mappedBy: 'LikecomId')]
    private Collection $likeCommentaires;

    public function __construct()
    {
        $this->likeCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->date_commentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $date_commentaire): static
    {
        $this->date_commentaire = $date_commentaire;

        return $this;
    }

    public function getFilms(): ?Films
    {
        return $this->films;
    }

    public function setFilms(?Films $films): static
    {
        $this->films = $films;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, LikeCommentaire>
     */
    public function getLikeCommentaires(): Collection
    {
        return $this->likeCommentaires;
    }

    public function addLikeCommentaire(LikeCommentaire $likeCommentaire): static
    {
        if (!$this->likeCommentaires->contains($likeCommentaire)) {
            $this->likeCommentaires->add($likeCommentaire);
            $likeCommentaire->setLikecomId($this);
        }

        return $this;
    }

    public function removeLikeCommentaire(LikeCommentaire $likeCommentaire): static
    {
        if ($this->likeCommentaires->removeElement($likeCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($likeCommentaire->getLikecomId() === $this) {
                $likeCommentaire->setLikecomId(null);
            }
        }

        return $this;
    }
}
