<?php

namespace Bizbozo\AdventOfCode\Year2023\Day02;


class Game
{

    /**
     * @var CubeDraw[]
     */
    public array $draws = [];

    /**
     * @param CubeDraw[] $draws
     */
    public function __construct(array $draws)
    {
        $this->draws = $draws;
    }

    public function isValid($red = 0, $green = 0, $blue = 0)
    {
        $minCubes = $this->getMinCubes();
        if ($minCubes->red > $red) return false;
        if ($minCubes->green > $green) return false;
        if ($minCubes->blue > $blue) return false;
        return true;
    }

    public function getMinCubes()
    {
        return array_reduce($this->draws, function ($carry, $draw) {
            $carry->riseMax($draw);
            return $carry;
        }, new CubeDraw());

    }
}
