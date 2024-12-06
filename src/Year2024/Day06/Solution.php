<?php

namespace Bizbozo\AdventOfCode\Year2024\Day06;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    private array $board;
    private int $width;

    private function get(int $x, int $y): ?string
    {
        if ($x < 0 || $y < 0) return null;
        if ($x >= $this->width || $y >= count($this->board)) return null;
        return $this->board[$y][$x];
    }

    private function board()
    {
        return implode(PHP_EOL, array_map(function ($row) {
            return implode('', $row);
        }, $this->board));
    }

    private function set(int $x, int $y, string $value): void
    {
        if ($x < 0 || $y < 0) return;
        if ($x >= $this->width || $y >= count($this->board)) return;
        $this->board[$y][$x] = $value;
    }

    public function getTitle(): string
    {
        return "Guard Gallivant";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $this->board = array_map('str_split', explode(PHP_EOL, $inputStream));
        $this->width = count($this->board[0]);
        $pos = strpos($inputStream, '^');
        $player_x = $pos % ($this->width + 1);
        $player_y = floor($pos / ($this->width + 1));
        $dirs = [
            [0, -1],
            [1, 0],
            [0, 1],
            [-1, 0]
        ];
        $dir = 0;
        $count = 0;
        while ($tile = $this->get($player_x, $player_y)) {
            if ($tile == '#') {
                $player_x -= $dirs[$dir][0];
                $player_y -= $dirs[$dir][1];
                $dir = ($dir + 1) % 4;
            }
            if ($tile == '.' || $tile=='^') {
                $this->set($player_x, $player_y, 'X');
                $count++;
            }
            $player_x += $dirs[$dir][0];
            $player_y += $dirs[$dir][1];

            //echo $this->board();
        }


        return new SolutionResult(
            6,
            new UnitResult("The 1st answer is %s", [$count]),
            new UnitResult('The 2nd answer is %s', [0])
        );
    }
}
