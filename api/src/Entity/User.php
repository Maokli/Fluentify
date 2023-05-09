<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private $roles = [];
 
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: UserQuizz::class)]
    private Collection $userQuizzes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preferredLanguages = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPreferredLanguages(): ?string
    {
        return $this->preferredLanguages;
    }

    public function setPreferredLanguages(string $preferredLanguages): self
    {
        $this->preferredLanguages = $preferredLanguages;

        return $this;
    }

     /**
     * @return Collection<int, Quizz>
     */
    public function getUserQuizzes(): Collection
    {
        return $this->userQuizzes;
    }

    public function addUserQuizz(UserQuizz $quizz): self
    {
        if (!$this->userQuizzes->contains($quizz)) {
            $this->userQuizzes->add($quizz);
            $quizz->setOwner($this);
        }

        return $this;
    }

    public function removeQuizz(UserQuizz $quizz): self
    {
        if ($this->userQuizzes->removeElement($quizz)) {
            // set the owning side to null (unless already changed)
            if ($quizz->getOwner() === $this) {
                $quizz->setOwner(null);
            }
        }

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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
 
        return array_unique($roles);
    }
 
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
 
        return $this;
    }
 
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
 
    public function setPassword(string $password): self
    {
        $this->password = $password;
 
        return $this;
    }
 
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
