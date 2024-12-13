<?php

namespace Bizbozo\AdventOfCode\Year2024\Day03;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    /**
     * @param string $inputStream
     * @param $matches
     * @param float|int $sum
     * @return array
     */
    public function compute(string $inputStream): int
    {
        $sum=0;
        preg_match_all('/mul\((\d+),(\d+)\)/', $inputStream, $matches);
        foreach ($matches[1] as $id => $match) {
            $sum += $match * $matches[2][$id];
        }
        return $sum;
    }

    /**
     * @param string $inputStream
     * @return array|int
     */
    public function compute2(string $inputStream): int|array
    {
        $p = explode("do()", $inputStream);
        $sum2 = 0;
        foreach ($p as $id => $match) {
            $parts = explode("don't()", $match);
            $sum2 += $this->compute($parts[0]);
        }
        return $sum2;
    }

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Mull It Over";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $sum = $this->compute($inputStream);

        $sum2 = $this->compute2($inputStream2 ?? $inputStream);

        return new SolutionResult(
            3,
            new UnitResult("%s is the output of the fixed program", [$sum]),
            new UnitResult('When only the enabled multiplications are considered, the correct output is %s', [$sum2])
        );
    }
}
