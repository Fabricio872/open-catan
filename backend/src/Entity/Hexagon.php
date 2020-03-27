<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HexagonRepository")
 */
class Hexagon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $position = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Session", inversedBy="hexagons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?array
    {
        return $this->position;
    }

    public function setPosition(array $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
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
}
