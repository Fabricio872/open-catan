<?php

namespace App\Service;

use App\Entity\Hexagon;
use App\Entity\Session;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class Playground
{
    private EntityManagerInterface $em;
    private Session $session;
    private int $level = 2;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
        Random::inst($session->getSeed());
    }

    public function getPlan(): Collection
    {
        if ($this->session->getHexagons()->count() == 0) {
            $this->generateLand();
            $this->generateWater();
        }
        $this->em->refresh($this->session);

        return $this->session->getHexagons();
    }

    private function generateLand()
    {
        $i = 0;
        $table = Random::inst()->shuffle($this->getTable());

        $tableNumber = Random::inst()->shuffle($this->getTableNumbers());
        foreach (Hex::cubeArea($this->level) as $coordinate) {
            $hexagon = new Hexagon();
            $hexagon
                ->setSession($this->session)
                ->setPosition($coordinate);

            if (empty(array_filter($coordinate))) {
                $hexagon
                    ->setType(0)
                    ->setValue(7);
            } else {
                $hexagon
                    ->setType($table[$i])
                    ->setValue($tableNumber[$i]);
                $i++;
            }

            $this->em->persist($hexagon);
        }
        $this->em->flush();
    }

    private function generateWater()
    {
        foreach (Hex::cubeRing($this->level + 1) as $coordinate) {

            $hexagon = new Hexagon();
            $hexagon
                ->setSession($this->session)
                ->setValue(0)
                ->setType(0)
                ->setPosition($coordinate);

            $this->em->persist($hexagon);
        }
        $this->em->flush();
    }

    private function getTable(): array
    {

        $rations = [
            1 => 4,
            2 => 4,
            3 => 3,
            4 => 3,
            5 => 4,
        ];
        return $this->getRatioTable($rations);
    }

    private function getTableNumbers()
    {
        $rations = [
            2=>1,
            3=>2,
            4=>2,
            5=>2,
            6=>2,
            8=>2,
            9=>2,
            10=>2,
            11=>2,
            12=>1,
        ];
        return $this->getRatioTable($rations);
    }

    private function getRatioTable(array $ratios): array
    {
        $result = [];
        foreach ($ratios as $key => $ratio) {
            $realRatio = Hex::totalInRadius($this->level) / array_sum($ratios) * $ratio;
            for ($i = 0; $i < $realRatio; $i++) {
                $result[] = $key;
            }
        }
        return $result;
    }
}