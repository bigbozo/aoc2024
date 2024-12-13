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

    /**
     * @param mixed $plot
     * @param mixed $cell
     * @param float|int $discountPrice
     * @return array
     */
    public function calculatePlotDiscountPrice(array $plot): int
    {
        $discountPrice = 0;
        $area = count($plot);

        $cols = ArrayUtility::partition($plot, fn($cell) => $cell['pos'][0]);
        $fenceCount = 0;
        foreach ($cols as $col) {
            usort($col, fn($a, $b) => $a['pos'][1] <=> $b['pos'][1]);
            $left = 0;
            $right = 0;
            $oldPos = false;
            while ($cell = array_shift($col)) {
                if (isset($cell['fences']['l'])) {
                    if ($oldPos !== false && $cell['pos'][1] - $oldPos > 1 && $left) {
                        $fenceCount++;
                    }
                    $left = 1;
                } else {
                    $fenceCount += $left;
                    $left = 0;
                }
                if (isset($cell['fences']['r'])) {
                    if ($oldPos !== false && $cell['pos'][1] - $oldPos > 1 && $right) {
                        $fenceCount++;
                    }
                    $right = 1;
                } else {
                    $fenceCount += $right;
                    $right = 0;
                }
                $oldPos = $cell['pos'][1];
            }
            $fenceCount += $left + $right;
        }
        $rows = ArrayUtility::partition($plot, fn($cell) => $cell['pos'][1]);
        foreach ($rows as $row) {
            usort($row, fn($a, $b) => $a['pos'][0] <=> $b['pos'][0]);
            $top = 0;
            $bottom = 0;
            $oldPos = false;
            while ($cell = array_shift($row)) {
                if (isset($cell['fences']['t'])) {
                    if ($oldPos !== false && $cell['pos'][0] - $oldPos > 1 && $top) {
                        $fenceCount++;
                    }
                    $top = 1;
                } else {
                    $fenceCount += $top;
                    $top = 0;
                }
                if (isset($cell['fences']['b'])) {
                    if ($oldPos !== false && $cell['pos'][0] - $oldPos > 1 && $bottom) {
                        $fenceCount++;
                    }
                    $bottom = 1;
                } else {
                    $fenceCount += $bottom;
                    $bottom = 0;
                }
                $oldPos = $cell['pos'][0];
            }
            $fenceCount += $top + $bottom;
            $top = 0;
        }

        return $area * $fenceCount;
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

                $newNeighbours = array_values(array_filter(
                    $board->findNeighbours($neighbour['pos']),
                    fn($n) => !$n['plot'] && $n['tile'] == $cell['tile']
                ));

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
            $cell['fences'] =
                array_flip(array_merge(
                    array_diff(
                        ['r', 'l', 'b', 't'],
                        array_keys($neighbours)
                    ),
                    array_keys($foreignNeighbours)
                ));
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
        foreach ($plots as $plot) {
            $discountPrice += $this->calculatePlotDiscountPrice($plot);
        }

        return new SolutionResult(
            12,
            new UnitResult("It will cost %s to fence all regions", [$price]),
            new UnitResult('The 2nd answer is %s', [$discountPrice])
        );
    }
}
