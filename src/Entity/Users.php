<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;
    
    #[ORM\Column(length: 100)]
    private ?string $name = null;
   
    #[ORM\Column(length: 100)]
    private ?string $firstname = null;
    
    #[ORM\Column(length: 255)]
    private ?string $email = null;
    
    #[ORM\Column(nullable: true)]
    private ?bool $banni = null;

    
    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'users', orphanRemoval: true)]
    private Collection $usercomment;

    /**
     * @var Collection<int, Likes>
     */
    #[ORM\OneToMany(targetEntity: Likes::class, mappedBy: 'likeuser', orphanRemoval: true)]
    private Collection $likes;

    /**
     * @var list<string> The user role
     */
    #[ORM\Column]
    private array $role = [];

    /**
     * @var Collection<int, LikeCommentaire>
     */
    #[ORM\OneToMany(targetEntity: LikeCommentaire::class, mappedBy: 'likeUser')]
    private Collection $likeCommentaires;

    public function __construct()
    {
        $this->usercomment = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->likeCommentaires = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isBanni(): ?bool
    {
        return $this->banni;
    }

    public function setBanni(?bool $banni): static
    {
        $this->banni = $banni;

        return $this;
    }

      /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

   
    
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getUsercomment(): Collection
    {
        return $this->usercomment;
    }

    public function addUsercomment(Commentaires $usercomment): static
    {
        if (!$this->usercomment->contains($usercomment)) {
            $this->usercomment->add($usercomment);
            $usercomment->setUsers($this);
        }

        return $this;
    }

    public function removeUsercomment(Commentaires $usercomment): static
    {
        if ($this->usercomment->removeElement($usercomment)) {
            // set the owning side to null (unless already changed)
            if ($usercomment->getUsers() === $this) {
                $usercomment->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Likes>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setLikeuser($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getLikeuser() === $this) {
                $like->setLikeuser(null);
            }
        }

        return $this;
    }
     /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $role = $this->role;
        // guarantee every user at least has ROLE_USER
        $role[] = 'ROLE_USER';

        return array_unique($role);
    }
     /**
     * @param list<string> $roles
     */
    public function setRoles(array $role): static
    {
        $this->role = $role;

        return $this;
    }

     /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $likeCommentaire->setLikeUser($this);
        }

        return $this;
    }

    public function removeLikeCommentaire(LikeCommentaire $likeCommentaire): static
    {
        if ($this->likeCommentaires->removeElement($likeCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($likeCommentaire->getLikeUser() === $this) {
                $likeCommentaire->setLikeUser(null);
            }
        }

        return $this;
    }
}
