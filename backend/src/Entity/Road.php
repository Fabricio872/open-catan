<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoadRepository")
 */
class Road implements BuilderInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="settlements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Hexagon", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $hex1;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Hexagon", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $hex2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getHex1(): ?Hexagon
    {
        return $this->hex1;
    }

    public function setHex1(Hexagon $hex1): self
    {
        $this->hex1 = $hex1;

        return $this;
    }

    public function getHex2(): ?Hexagon
    {
        return $this->hex2;
    }

    public function setHex2(Hexagon $hex2): self
    {
        $this->hex2 = $hex2;

        return $this;
    }
}
