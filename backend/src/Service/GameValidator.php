<?php

namespace App\Service;

use App\Entity\BuilderInterface;
use App\Entity\Hexagon;
use App\Entity\Player;
use App\Entity\Road;
use App\Entity\Settlement;
use Doctrine\ORM\EntityManagerInterface;

class GameValidator
{
    /** @var array|Hexagon $hexagons */
    private array $hexagons = [];
    private EntityManagerInterface $em;
    private Player $player;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Hexagon ...$hexagons
     */
    public function setHexagons(...$hexagons)
    {
        $this->hexagons = $hexagons;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    public function isOnWater(): bool
    {
        /** @var Hexagon $hexagon */
        foreach ($this->hexagons as $hexagon) {
            if ($hexagon->getType() != 6) {
                return false;
            }
        }
        return true;
    }

    public function isNotNeighbors(): bool
    {
        /** @var Hexagon $hexagonA */
        /** @var Hexagon $hexagonB */
        foreach ($this->hexagons as $hexagonA) {
            foreach ($this->hexagons as $hexagonB) {
                if ($hexagonA->getId() != $hexagonB->getId()) {
                    if (Hex::cubeDistance($hexagonA->getPosition(), $hexagonB->getPosition()) > 1) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function isRoadNearSettlement(): bool
    {
        foreach ($this->em->getRepository(Settlement::class)->findBy([
            "player" => $this->player
        ]) as $settlement) {
            $hexIds = [
                $settlement->getHex1()->getId(),
                $settlement->getHex2()->getId(),
                $settlement->getHex3()->getId()
            ];

            if (in_array($this->hexagons[0]->getId(), $hexIds) &&
                in_array($this->hexagons[1]->getId(), $hexIds)) {
                return false;
            }
        }
        return true;
    }

    public function isRoadNotNearRoad(): bool
    {
        foreach ($this->em->getRepository(Road::class)->findBy([
            "player" => $this->player
        ]) as $road) {
            $hexIds = [
                $road->getHex1()->getId(),
                $road->getHex2()->getId()
            ];

            if (in_array($this->hexagons[0]->getId(), $hexIds) ||
                in_array($this->hexagons[1]->getId(), $hexIds)) {
                return false;
            }
        }
        return true;
    }

    public function getEntity(): BuilderInterface
    {
        if (count($this->hexagons) == 3) {
            $entity = new Settlement();
        } else {
            $entity = new Road();
        }

        $entity->setPlayer($this->player);
        foreach ($this->hexagons as $key => $hexagon) {
            $setterName = "setHex" . ($key + 1);
            $entity->$setterName($hexagon);
        }
        return $entity;
    }
}