<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Error;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields="email", message="Le mail existe déja")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * * @Error\Email(
     *     message = "Ton Email '{{ value }}' n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Error\Length(min="8", minMessage="ton mot de passe doit contenir au moins 8 caractere")
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $age;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Error\Length(min=3, max=255, minMessage="ton prénom '{{ value }}' est trop court", 
     * maxMessage="Ton prénom '{{ value }}' est trop long")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Error\Length(min=3, max=255, minMessage="ton prénom '{{ value }}' est trop court", 
     * maxMessage="Ton prénom '{{ value }}' est trop long")
     */
    private $surname;

    private $username;

    /**
     * @ORM\OneToMany(targetEntity=WentOut::class, mappedBy="user")
     */
    private $wentOuts;

    /**
     * @ORM\OneToMany(targetEntity=WeekYears::class, mappedBy="user")
     */
    private $weekYears;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCoach;

    /**
     * @ORM\OneToMany(targetEntity=Coach::class, mappedBy="coach")
     */
    private $coaches;

    public function __construct()
    {
        $this->wentOuts = new ArrayCollection();
        $this->weekYears = new ArrayCollection();
        $this->coaches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|WentOut[]
     */
    public function getWentOuts(): Collection
    {
        return $this->wentOuts;
    }

    public function addWentOut(WentOut $wentOut): self
    {
        if (!$this->wentOuts->contains($wentOut)) {
            $this->wentOuts[] = $wentOut;
            $wentOut->setUser($this);
        }

        return $this;
    }

    public function removeWentOut(WentOut $wentOut): self
    {
        if ($this->wentOuts->removeElement($wentOut)) {
            // set the owning side to null (unless already changed)
            if ($wentOut->getUser() === $this) {
                $wentOut->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WeekYears[]
     */
    public function getWeekYears(): Collection
    {
        return $this->weekYears;
    }

    public function addWeekYear(WeekYears $weekYear): self
    {
        if (!$this->weekYears->contains($weekYear)) {
            $this->weekYears[] = $weekYear;
            $weekYear->setUser($this);
        }

        return $this;
    }

    public function removeWeekYear(WeekYears $weekYear): self
    {
        if ($this->weekYears->removeElement($weekYear)) {
            // set the owning side to null (unless already changed)
            if ($weekYear->getUser() === $this) {
                $weekYear->setUser(null);
            }
        }

        return $this;
    }

    public function getIsCoach(): ?bool
    {
        return $this->isCoach;
    }

    public function setIsCoach(bool $isCoach): self
    {
        $this->isCoach = $isCoach;

        return $this;
    }

    /**
     * @return Collection|Coach[]
     */
    public function getCoaches(): Collection
    {
        return $this->coaches;
    }

    public function addCoach(Coach $coach): self
    {
        if (!$this->coaches->contains($coach)) {
            $this->coaches[] = $coach;
            $coach->setCoach($this);
        }

        return $this;
    }

    public function removeCoach(Coach $coach): self
    {
        if ($this->coaches->removeElement($coach)) {
            // set the owning side to null (unless already changed)
            if ($coach->getCoach() === $this) {
                $coach->setCoach(null);
            }
        }

        return $this;
    }
}
