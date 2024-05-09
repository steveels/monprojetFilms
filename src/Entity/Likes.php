<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesRepository::class)]
class Likes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $likeuser = null;

    #[ORM\ManyToOne(inversedBy: 'likesfilm')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Films $filmslikes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getLikeuser(): ?Users
    {
        return $this->likeuser;
    }

    public function setLikeuser(?Users $likeuser): static
    {
        $this->likeuser = $likeuser;

        return $this;
    }

    public function getFilmslikes(): ?Films
    {
        return $this->filmslikes;
    }

    public function setFilmslikes(?Films $filmslikes): static
    {
        $this->filmslikes = $filmslikes;

        return $this;
    }
}
