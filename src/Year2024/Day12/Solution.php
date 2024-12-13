<?php

namespace Bizbozo\AdventOfCode\Year2024\Day12;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Structures\Board;
use Bizbozo\AdventOfCode\Utility\ArrayUtility;
use Override;

class Solution implements SolutionInterface
{

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Garden Groups";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $time = microtime(true);
        $board = new Board($inputStream);
        $board->each(fn($cell) => $cell + ['plot' => false]);

        // First mark the plots
        $plotId = 0;
        $cell = ['pos' => [0, 0]];
        while ($cell = $board->first(fn($cell) => !$cell['plot'], $cell)) {

            $plotId++;
            $cell['plot'] = $plotId;
            $board->update($cell);
            $neighbours = [$cell];

            while (count($neighbours)) {
                $neighbour = array_shift($neighbours);

                $newNeighbours = array_filter(
                    $board->findNeighbours($neighbour['pos']),
                    fn($n) => !$n['plot'] && $n['tile'] == $cell['tile']
                );

                foreach ($newNeighbours as $neighbour) {
                    $neighbour['plot'] = $plotId;
                    $board->update($neighbour);
                }

                $neighbours = array_merge(
                    $neighbours,
                    $newNeighbours
                );
            }
        }

        // count the fences at each cell
        $board->each(function ($cell) use ($board) {
            $neighbours = $board->findNeighbours($cell['pos']);
            $foreignNeighbours = array_filter($neighbours, fn($neighbour) => $neighbour['plot'] != $cell['plot']);
            $cell['count'] =
                4 - count($neighbours)
                + count($foreignNeighbours);
            return $cell;
        });

        // partition the board in plots
        $plots = $board->partition(fn($cell) => $cell['plot']);

        // calculate fence prices
        $price = array_sum(
            array_map(
                fn($plot) => count($plot) * array_sum(array_column($plot, 'count')),
                $plots
            )
        );

        $discountPrice = 0;

        return new SolutionResult(
            12,
            new UnitResult("It will cost %s to fence all regions", [$price]),
            new UnitResult('The 2nd answer is %s', [$discountPrice])
        );
    }
}
