<?php

namespace App\Controller;

use InvalidArgumentException;

class Utils
{

    static function factorielle(int $n): int
    {
        if ($n < 0) {
            throw new InvalidArgumentException("La valeur de n doit être un entier non négatif.");
        }

        return $n === 0 ? 1 : $n * self::factorielle($n - 1);
    }

    static function combinaison(int $n, int $p) {
        if ($n < 0 || $p < 0) {
            throw new InvalidArgumentException("La valeur de n doit être un entier non négatif.");
        }
        return self::factorielle($n) / (self::factorielle($p) * self::factorielle($n - $p));
    }


}