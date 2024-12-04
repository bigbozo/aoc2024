<?php

namespace Bizbozo\AdventOfCode\Year2024\Day04;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    /**
     * @var array|array[]
     */
    private array $data;
    private int $width;
    private int $height;

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Ceres Search";
    }

    private function get($x, $y)
    {
        if ($x < 0 || $y < 0) {
            return null;
        };
        if ($x > $this->width - 1 || $y > $this->height - 1) {
            return null;
        };
        return $this->data[$y][$x] ??
            null;
    }

    #[Override] public function solve(string $inputStream, string $inputStream2 = null): SolutionResult
    {

        $this->data = array_map(function ($line) {
            return str_split($line);
        }, explode("\n", $inputStream));
        $this->height = count($this->data);
        $this->width = count($this->data[0]);
        $result = 0;
        foreach ($this->data as $y => $line) {
            foreach ($line as $x => $char) {
                $result += $this->walk($x, $y, 'XMAS');
            }
        }

        foreach ($this->data as $y => $line) {
            foreach ($line as $x => $char) {
                $result2 += $this->cross($x, $y);
            }
        }

        return new SolutionResult(
            4,
            new UnitResult("The 1st answer is %s", [$result]),
            new UnitResult('The 2nd answer is %s', [$result2])
        );
    }

    private function walk(int|string $x, int|string $y, string $query, $dx = null, $dy = null)
    {
        $char = $this->get($x, $y);
        if ($char != substr($query, 0, 1)) return 0;
        if (strlen($query) === 1) {
            return 1;
        }
        $query = substr($query, 1);
        if ($dx === null) {
            $c = 0;
            for ($i = -1; $i <= 1; $i++) {
                for ($j = -1; $j <= 1; $j++) {
                    if ($i || $j) {
                        $c += $this->walk($x + $i, $y + $j, $query, $i, $j);
                    }
                }
            }
            return $c;
        } else {
            return $this->walk($x + $dx, $y + $dy, $query, $dx, $dy);
        }
    }

    private function cross(int|string $x, int|string $y)
    {
        $char = $this->get($x, $y);
        if ($char != 'A') return 0;
        for ($i = -1; $i <= 1; $i += 2) {
            for ($j = -1; $j <= 1; $j += 2) {
                if ($this->get($x + $i, $y + $j) == 'M' && $this->get($x - $i, $y - $j) == 'S') {
                    if (
                        ($this->get($x+$i,$y-$j)=='M' && $this->get($x-$i,$y+$j)=='S') ||
                        ($this->get($x-$i,$y+$j)=='M' && $this->get($x+$i,$y-$j)=='S')
                    ) {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }
}
