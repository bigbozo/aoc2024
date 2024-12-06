<?php

namespace Bizbozo\AdventOfCode\Year2024\Day06;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Structures\Board;
use Override;

class Solution implements SolutionInterface
{

    public function getTitle(): string
    {
        return "Guard Gallivant";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        $board = new Board($inputStream);
        $pos = strpos($inputStream, '^');
        $player_x = $pos % ($board->width + 1);
        $player_y = floor($pos / ($board->width + 1));
        $dirs = [
            [0, -1],
            [1, 0],
            [0, 1],
            [-1, 0]
        ];

        $dir = 0;
        $count = 0;
        while ($tile = $board->get($player_x, $player_y)['tile'] ?? null) {
            if ($tile == '#') {
                $player_x -= $dirs[$dir][0];
                $player_y -= $dirs[$dir][1];
                $dir = ($dir + 1) % 4;
            }
            if ($tile == '.' || $tile=='^') {
                $board->set($player_x, $player_y, ['tile'=>'X']);
                $count++;
            }
            $player_x += $dirs[$dir][0];
            $player_y += $dirs[$dir][1];
        }


        return new SolutionResult(
            6,
            new UnitResult("The 1st answer is %s", [$count]),
            new UnitResult('The 2nd answer is %s', [0])
        );
    }
}
