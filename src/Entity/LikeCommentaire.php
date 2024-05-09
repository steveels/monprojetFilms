<?php

namespace App\Entity;

use App\Repository\LikeCommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeCommentaireRepository::class)]
class LikeCommentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likeCommentaires')]
    private ?Users $likeUser = null;

    #[ORM\ManyToOne(inversedBy: 'likeCommentaires')]
    private ?Commentaires $LikecomId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikeUser(): ?Users
    {
        return $this->likeUser;
    }

    public function setLikeUser(?Users $likeUser): static
    {
        $this->likeUser = $likeUser;

        return $this;
    }

    public function getLikecomId(): ?Commentaires
    {
        return $this->LikecomId;
    }

    public function setLikecomId(?Commentaires $LikecomId): static
    {
        $this->LikecomId = $LikecomId;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
