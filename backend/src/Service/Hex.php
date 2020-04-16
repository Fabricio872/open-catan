<?php

namespace App\Service;

class Hex
{
    public static function cubeArea(int $diameter): array
    {
        $coordinates = [];
        for ($x = -$diameter; $x <= $diameter; $x++) {
            for ($y = max(-$diameter, -$x - $diameter); $y <= min($diameter, -$x + $diameter); $y++) {
                $z = -$x - $y;
                $coordinates[] = [$x, $y, $z];
            }
        }
        return $coordinates;
    }

    public static function cubeRing(int $radius, array $center = [0, 0, 0]): array
    {
        $results = [];
        $cube = $center;
        for ($i = 0; $i < $radius; $i++) {
            $cube = self::cubeAdd(self::cubeDirection(4), $cube);
        }

        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < $radius; $j++) {
                $results[] = $cube;
                $cube = self::cubeNeighbor($cube, $i);
            }
        }
        return $results;
    }

    public static function cubeDistance(array $a, array $b = [0, 0, 0])
    {
        return (abs($a[0] - $b[0]) + abs($a[1] - $b[1]) + abs($a[2] - $b[2])) / 2;
    }

    public static function cubeNeighbor(array $cube, int $direction)
    {
        return self::cubeAdd($cube, self::cubeDirection($direction));
    }

    public static function cubeDirection(int $direction): array
    {
        $directions = [
            [1, -1, 0],
            [1, 0, -1],
            [0, 1, -1],
            [-1, 1, 0],
            [-1, 0, 1],
            [0, -1, 1]
        ];
        return $directions[$direction];
    }

    public static function cubeAdd(array $cube1, array $cube2): array
    {
        $result = [];
        foreach (array_keys($cube1 + $cube2) as $key) {
            $result[$key] = $cube1[$key] + $cube2[$key];
        }
        return $result;
    }

    public static function cubeToOddr(array $cube): array
    {
        $col = $cube[0] + ($cube[2] - ($cube[2] % 1) / 2);
        $row = $cube[2];
        return [$col, $row];
    }

    public static function totalInRadius(int $radius)
    {
        $total = 0;
        for ($i = $radius; $i > 0; $i--) {
            $total += 6 * $i;
        }
        return $total;
    }
}