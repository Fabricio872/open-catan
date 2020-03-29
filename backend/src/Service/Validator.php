<?php


namespace App\Service;


class Validator
{
    public static function isColor(string $color):bool
    {
        return preg_match_all('/^#[a-fA-f0-9]{6}$/', $color);
    }
}