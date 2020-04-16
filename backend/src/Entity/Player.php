<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Session", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isHost;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $color;

    /**
     * @ORM\Column(type="datetime")
     */
    private $online;

    /**
     * @ORM\OneToMany(targetEntity="Road", mappedBy="user")
     */
    private $roads;

    /**
     * @ORM\OneToMany(targetEntity="Settlement", mappedBy="player", orphanRemoval=true)
     */
    private $settlements;

    public function __construct()
    {
        $this->isHost = false;
        $this->online = new \DateTime("now");
        $this->roads = new ArrayCollection();
        $this->settlements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

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

    public function getIsHost(): ?bool
    {
        return $this->isHost;
    }

    public function setIsHost(bool $isHost): self
    {
        $this->isHost = $isHost;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getOnline(): ?\DateTimeInterface
    {
        return $this->online;
    }

    public function getIsOnline(): bool
    {
        return ($this->online > new \DateTime("now -10seconds"));
    }

    public function setOnline(\DateTimeInterface $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @return Collection|Road[]
     */
    public function getRoads(): Collection
    {
        return $this->roads;
    }

    public function addRoad(Road $road): self
    {
        if (!$this->roads->contains($road)) {
            $this->roads[] = $road;
            $road->setPlayer($this);
        }

        return $this;
    }

    public function removeRoad(Road $road): self
    {
        if ($this->roads->contains($road)) {
            $this->roads->removeElement($road);
            // set the owning side to null (unless already changed)
            if ($road->getPlayer() === $this) {
                $road->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Settlement[]
     */
    public function getSettlements(): Collection
    {
        return $this->settlements;
    }

    public function addSettlement(Settlement $settlement): self
    {
        if (!$this->settlements->contains($settlement)) {
            $this->settlements[] = $settlement;
            $settlement->setPlayer($this);
        }

        return $this;
    }

    public function removeSettlement(Settlement $settlement): self
    {
        if ($this->settlements->contains($settlement)) {
            $this->settlements->removeElement($settlement);
            // set the owning side to null (unless already changed)
            if ($settlement->getPlayer() === $this) {
                $settlement->setPlayer(null);
            }
        }

        return $this;
    }
}
