<?php

namespace Bizbozo\AdventOfCode\Year2023\Day11;

class GalaxyField
{
    private int $width;
    private int $height;

    private array $h_shift;
    private array $v_shift;
    private array $galaxies;

    public function __construct($width, $height)
    {

        $this->width = $width;
        $this->height = $height;
        $this->h_shift = array_fill(0, $width, 1);
        $this->v_shift = array_fill(0, $height, 1);
    }

    public function addGalaxy($x, $y)
    {
        $this->galaxies[] = [$x, $y];
        $this->h_shift[$x] = 0;
        $this->v_shift[$y] = 0;
    }

    public function distances($expansion = 1)
    {
        $distances = 0;
        for ($i = 0; $i < count($this->galaxies) - 1; $i++) {
            for ($j = $i + 1; $j < count($this->galaxies); $j++) {
                $distance = $this->calcDistance($i, $j, $expansion);
                $distances += $distance;
            }
        }
        return $distances;
    }

    private function calcDistance(mixed $left_id, mixed $right_id, int $expansion)
    {
        $left = $this->galaxies[$left_id];
        $right = $this->galaxies[$right_id];

        $x0 = min($left[0], $right[0]);
        $x1 = max($left[0], $right[0]);
        $y0 = min($left[1], $right[1]);
        $y1 = max($left[1], $right[1]);
        $distance = $x1 - $x0 + $y1 - $y0;
        $shifts = 0;
        for ($x = $x0; $x < $x1; $x++) {
            $shifts += $this->h_shift[$x];
        }
        for ($y = $y0; $y < $y1; $y++) {
            $shifts += $this->v_shift[$y];
        }
        return $distance + $shifts * ($expansion-1);
    }


}
