<?php

namespace Bizbozo\AdventOfCode\Year2024\Day10;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Structures\Board;
use Override;

class Solution implements SolutionInterface
{

    public function getTitle(): string
    {
        return "Hoof It";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $board = new Board($inputStream);

        $queue = array_map(
            fn($cell) => [
                /* step */
                0,
                /* position */
                $cell['pos'],
                /* origin */
                $cell['id']
            ],
            $board->find(fn($cell) => $cell['tile'] == '0')
        );

        while ($queue) {

            [$counter, $currentPosition, $origin] = array_shift($queue);

            //echo sprintf("%s - %s - %s/%s\n", $origin, $counter, ...$currentPosition);

            $neighbours = $board->findNeighbours($currentPosition);

            $nextStep = array_map(
                function ($cell) use ($counter, $origin, $board) {

                    $board->board[$cell['pos'][1]][$cell['pos'][0]]['visitors'][$origin]
                        = ($board->board[$cell['pos'][1]][$cell['pos'][0]]['visitors'][$origin] ?? 0) + 1;

                    return [
                        $counter + 1,
                        $cell['pos'],
                        $origin
                    ];
                },
                array_filter(
                    $neighbours,
                    fn($cell) => $cell['tile'] == $counter + 1
                )
            );


            $queue = array_merge($queue, $nextStep);
        }

        $trailFoots = $board->find(
            fn($item) => $item['tile'] == 9
        );

        $sum = array_sum(array_map(
            fn($item) => count($item['visitors']),
            $trailFoots
        ));

        $sum2 = array_sum(array_map(
            fn($item) => array_sum($item['visitors'] ?? []),
            $trailFoots
        ));


        return new SolutionResult(
            10,
            new UnitResult("The 1st answer is %s", [$sum]),
            new UnitResult('The 2nd answer is %s', [$sum2])
        );
    }
}
