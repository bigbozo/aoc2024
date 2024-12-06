<?php

namespace Bizbozo\AdventOfCode\Year2024\Day06;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Structures\Board;
use Override;

class Solution implements SolutionInterface
{

    private array $dirs = [
        [0, -1],
        [1, 0],
        [0, 1],
        [-1, 0]
    ];


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

        $board1 = clone $board;
        $count = $this->computeGuardiansPath($board1, $player_x, $player_y);
        $count2 = 0;

        $board->each(fn($cell) => ['tile' => $cell['tile'], 'dirs' => [false, false, false, false]]);

        for ($x = 0; $x < $board->width; $x++) {
            for ($y = 0; $y < $board->height; $y++) {
                $testBoard = clone $board;
                if ($board1->board[$y][$x]['tile'] == 'X') {
                    $testBoard->board[$y][$x] = ['tile' => '#'];
                    $count2 += $this->testGuardianIsLooping($testBoard, $player_x, $player_y) ? 1 : 0;
                }
            }
            // echo !($x % 10) ? '|' : (!($x % 2) ? '.' : '');
        }
        // echo PHP_EOL;

        return new SolutionResult(
            6,
            new UnitResult("The guard visits %s distinct locations", [$count]),
            new UnitResult('The loop providing obstruction can be placed at %s locations', [$count2])
        );
    }

    /**
     * @param Board $board
     * @param int $player_x
     * @param float|int $player_y
     * @return int
     */
    public function computeGuardiansPath(Board $board, int $player_x, float|int $player_y): int
    {

        $dir = 0;
        $count = 0;
        while ($tile = $board->board[$player_y][$player_x]['tile'] ?? null) {
            if ($tile == '#') {
                $player_x -= $this->dirs[$dir][0];
                $player_y -= $this->dirs[$dir][1];
                $dir = ($dir + 1) % 4;
            }
            if ($tile == '.' || $tile == '^') {
                $board->board[$player_y][$player_x] = ['tile' => 'X'];
                $count++;
            }
            $player_x += $this->dirs[$dir][0];
            $player_y += $this->dirs[$dir][1];
        }
        return $count;
    }

    private function testGuardianIsLooping(Board $board, int $player_x, float $player_y): bool
    {
        $dir = 0;

        while ($tile = $board->board[$player_y][$player_x] ?? null) {
            if ($tile['tile'] == 'X' && $tile['dirs'][$dir]) {
                return true;
            }
            if ($tile['tile'] == '.' || $tile['tile'] == '^' || $tile['tile'] == 'X') {
                $tile['tile'] = 'X';
                $tile['dirs'][$dir] = true;
                $board->board[$player_y][$player_x] = $tile;
            }
            if ($tile['tile'] == '#') {
                $player_x -= $this->dirs[$dir][0];
                $player_y -= $this->dirs[$dir][1];

                $dir = ($dir + 1) % 4;
            }
            $player_x += $this->dirs[$dir][0];
            $player_y += $this->dirs[$dir][1];

            //   $board->print();
        }
        return false;
    }
}
