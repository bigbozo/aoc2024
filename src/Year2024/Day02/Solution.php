<?php

namespace Bizbozo\AdventOfCode\Year2024\Day02;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Red-Nosed Reports";
    }

    #[Override] public function solve(string $inputStream, string $inputStream2 = null): SolutionResult
    {

        $save = 0;
        $lines = explode(PHP_EOL, $inputStream);
        $valid = 0;
        foreach ($lines as $l) {
            if (trim($l)) {
                $n = explode(' ', $l);

                $s = array_shift($n);
                $distances = [];
                foreach ($n as $num) {
                    $distances[] = $num - $s;
                    $s = $num;
                }
                sort($distances);
                $low = array_shift($distances);
                $high = array_pop($distances);
                if (
                    (($low > 0 && $high > 0) || ($low < 0 && $high < 0))
                    && max(abs($high),abs($low)) <= 3) {
                    $valid++;
                }
            }
        }

        return new SolutionResult(
            2,
            new UnitResult("%s reports are safe", [$valid]),
            new UnitResult('The 2nd answer is %s', [0])
        );
    }
}
