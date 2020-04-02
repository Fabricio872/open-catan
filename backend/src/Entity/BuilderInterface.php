<?php

namespace App\Entity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RoadsRepository")
 */
interface BuilderInterface
{
    public function setPlayer(?Player $player);

    public function setHex1(Hexagon $hex1);

    public function setHex2(Hexagon $hex2);
}