<?php

namespace App\Service;

class Mastermind
{
    private $code;
    private $history = [];

    public function __construct()
    {
        $this->generateCode();
    }

    private function generateCode()
    {
        $numbers = range(0, 9);
        shuffle($numbers);
        $this->code = array_slice($numbers, 0, 4);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function checkGuess(array $guess)
    {
        $bienPlaces = 0;
        $malPlaces = 0;

        $codeTemp = $this->code;
        $originalGuess = $guess;

        foreach ($guess as $index => $digit) {
            if ($digit == $this->code[$index]) {
                $bienPlaces++;
                $codeTemp[$index] = null;
                $guess[$index] = null;
            }
        }

        foreach ($guess as $index => $digit) {
            if ($digit !== null && in_array($digit, $codeTemp)) {
                $malPlaces++;
                $key = array_search($digit, $codeTemp);
                $codeTemp[$key] = null;
            }
        }

        $this->history[] = [
            'proposition' => implode('', $originalGuess),
            'bienPlaces' => $bienPlaces,
            'malPlaces' => $malPlaces
        ];

        return ['bienPlaces' => $bienPlaces, 'malPlaces' => $malPlaces];
    }

    public function getHistory()
    {
        return $this->history;
    }

    public function reset()
    {
        $this->history = [];
        $this->generateCode();
    }
}
