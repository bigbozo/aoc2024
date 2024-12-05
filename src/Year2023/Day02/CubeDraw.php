<?php

namespace Bizbozo\AdventOfCode\Year2023\Day02;

class CubeDraw
{
    public int $red = 0;
    public int $green = 0;
    public int $blue = 0;

    public function __construct($red = 0, $green = 0, $blue = 0)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public static function fromTest($test): CubeDraw
    {
        return new static(
            (int)($test['red'] ?? 0),
            (int)($test['green'] ?? 0),
            (int)($test['blue'] ?? 0)
        );
    }

    public function power()
    {
        return $this->red * $this->green * $this->blue;
    }

    public function riseMax(CubeDraw $draw)
    {
        $this->red = max($this->red, $draw->red);
        $this->green = max($this->green, $draw->green);
        $this->blue = max($this->blue, $draw->blue);
    }
}
