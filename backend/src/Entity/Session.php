<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerCount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $seed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="session", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hexagon", mappedBy="session", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $hexagons;

    public function __construct()
    {
        $this->date = new \DateTime("now");
        $this->players = new ArrayCollection();
        $this->hexagons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlayerCount(): ?int
    {
        return $this->playerCount;
    }

    public function setPlayerCount(int $playerCount): self
    {
        $this->playerCount = $playerCount;

        return $this;
    }

    public function getSeed(): ?string
    {
        return $this->seed;
    }

    public function setSeed(string $seed): self
    {
        $this->seed = $seed;

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

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setSession($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getSession() === $this) {
                $player->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Hexagon[]
     */
    public function getHexagons(): Collection
    {
        return $this->hexagons;
    }

    public function addHexagon(Hexagon $hexagon): self
    {
        if (!$this->hexagons->contains($hexagon)) {
            $this->hexagons[] = $hexagon;
            $hexagon->setSession($this);
        }

        return $this;
    }

    public function removeHexagon(Hexagon $hexagon): self
    {
        if ($this->hexagons->contains($hexagon)) {
            $this->hexagons->removeElement($hexagon);
            // set the owning side to null (unless already changed)
            if ($hexagon->getSession() === $this) {
                $hexagon->setSession(null);
            }
        }

        return $this;
    }
}
