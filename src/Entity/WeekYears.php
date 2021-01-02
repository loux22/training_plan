<?php

namespace App\Entity;

use App\Repository\WeekYearsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeekYearsRepository::class)
 */
class WeekYears
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $week;

    /**
     * @ORM\Column(type="integer")
     */
    private $years;

    /**
     * @ORM\Column(type="float")
     */
    private $km;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity=WentOut::class, mappedBy="week")
     */
    private $wentOuts;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="weekYears")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->wentOuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getYears(): ?int
    {
        return $this->years;
    }

    public function setYears(int $years): self
    {
        $this->years = $years;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

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
            $wentOut->setWeek($this);
        }

        return $this;
    }

    public function removeWentOut(WentOut $wentOut): self
    {
        if ($this->wentOuts->removeElement($wentOut)) {
            // set the owning side to null (unless already changed)
            if ($wentOut->getWeek() === $this) {
                $wentOut->setWeek(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
