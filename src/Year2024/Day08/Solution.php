<?php

namespace Bizbozo\AdventOfCode\Year2024\Day08;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Structures\Board;
use Override;

class Solution implements SolutionInterface
{

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Resonant Collinearity";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $board = new Board($inputStream);
        $antennaGroups = [];
        for ($x = 0; $x < $board->width; $x++) {
            for ($y = 0; $y < $board->height; $y++) {
                if ($board->board[$y][$x]['tile'] != '.') {
                    $antennaGroups[$board->board[$y][$x]['tile']][] = [$x, $y];
                }
            }
        }

        foreach ($antennaGroups as $antennas) {
            while (count($antennas) > 1) {
                $antenna = array_shift($antennas);
                foreach ($antennas as $antenna2) {
                    $v = [$antenna2[0] - $antenna[0], $antenna2[1] - $antenna[1]];
                    $board->set($antenna[0] - $v[0], $antenna[1] - $v[1], ['tile' => '#']);
                    $board->set($antenna[0] + 2 * $v[0], $antenna[1] + 2 * $v[1], ['tile' => '#']);
                }
            }
        }

        $score = 0;
        for ($x = 0; $x < $board->width; $x++) {
            for ($y = 0; $y < $board->height; $y++) {
                if ($board->board[$y][$x]['tile'] == '#') {
                    $score++;
                }
            }
        }

        foreach ($antennaGroups as $antennas) {
            while (count($antennas) > 1) {
                $antenna = array_shift($antennas);
                foreach ($antennas as $antenna2) {
                    $v = [$antenna2[0] - $antenna[0], $antenna2[1] - $antenna[1]];
                    $d = 0;
                    while ($board->setWithResponse($antenna[0] - $d * $v[0], $antenna[1] - $d * $v[1], ['tile' => '#'])) {
                        $d++;
                    };
                    $d = 1;
                    while ($board->setWithResponse($antenna[0] + $d * $v[0], $antenna[1] + $d * $v[1], ['tile' => '#'])) {
                        $d++;
                    };
                }
            }
        }

        $score2 = 0;
        for ($x = 0; $x < $board->width; $x++) {
            for ($y = 0; $y < $board->height; $y++) {
                if ($board->board[$y][$x]['tile'] == '#') {
                    $score2++;
                }
            }
        }


        return new SolutionResult(
                8,
                new UnitResult("There are %s location containing an antinode", [$score]),
                new UnitResult('Using the updated model there are %s locations', [$score2])
            );
        }
    }
