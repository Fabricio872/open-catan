<?php


namespace App\Service;


class Random
{
    private static $instance = null;

    private function __construct(int $seed)
    {
        mt_srand($seed);
    }

    public static function inst(int $seed = null)
    {
        if (self::$instance == null) {
            if ($seed == null){
                throw new \Exception("Seed must be specified on first instance");
            }
            self::$instance = new Random($seed);
        }

        return self::$instance;
    }

    public function shuffle(array $array)
    {
        $size = count($array);
        for ($i = 0; $i < $size; ++$i) {
            list($chunk) = array_splice($array, mt_rand(0, $size - 1), 1);
            array_push($array, $chunk);
        }
        return $array;
    }
}