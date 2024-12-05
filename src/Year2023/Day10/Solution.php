<?php

namespace Bizbozo\AdventOfCode\Year2023\Day10;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{


    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $board = Board::build($inputStream);
        $scores = [];
        $part2_scores = [];

        $start = $board->find('S');
        if (count($start)!==1) {
            throw new \OutOfBoundsException('Input data not valid');
        }
        $scores[] = $board->explore(...$start[0]);
        $part2_scores[] = $board->calculateArea();


        if ($inputStream2) {
            $board = Board::build($inputStream2);

            $start = $board->find('S');
            if (count($start) !== 1) {
                throw new \OutOfBoundsException('Input data not valid');
            }
            $scores[] = $board->explore(...$start[0]);
            $part2_scores[] = $board->calculateArea();
        }
        // 1100 is too much
        return new SolutionResult(
            10,
            new UnitResult('farthest point from the start', implode(' or ',$scores), 'pipes away'),
            new UnitResult('area inside the pipes', implode(' or ',$part2_scores), 'squares')
        );
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
