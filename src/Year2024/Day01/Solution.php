<?php

namespace Bizbozo\AdventOfCode\Year2024\Day01;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

    public function solve(string $inputStream, string $inputStream2 = null): SolutionResult
    {
        return new SolutionResult(
            1,
            new UnitResult("The 1st answer is %s", [0]),
            new UnitResult('The 2nd answer is %s',[0])
        );
    }

    public function getTitle(): string
    {
        return 'sdfkjhsdfkjhsdjkfh';
    }
}
