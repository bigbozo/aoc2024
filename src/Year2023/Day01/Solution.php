<?php

namespace Bizbozo\AdventOfCode\Year2023\Day01;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{
    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        $values = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'zero' => 0
        ];

        $sum1 = 0;
        foreach (explode("\n", $inputStream) as $line) {
            if (preg_match('/.*?(\d).*/', $line, $match)) {
                $left = (int)$match[1];
                if (preg_match('/.*(\d).*?/', $line, $match)) {
                    $sum1 += $left * 10 + (int)$match[1];
                }
            }
        }
        $sum2 = 0;
        foreach (explode("\n", $inputStream) as $line) {
            if (preg_match('/.*?(\d|one|two|three|four|five|six|seven|eight|nine|zero).*/', $line, $match)) {
                $left = $match[1];
                if (preg_match('/.*(\d|one|two|three|four|five|six|seven|eight|nine|zero).*?/', $line, $match)) {
                    $right = $match[1];

                    $number = (int)($values[$left] . $values[$right]);
                    $sum2 += $number;
                }
            }
        }

        return new SolutionResult(
            1,
            new UnitResult("calibration value-sum is %s cp", [$sum1], ' cp'),
            new UnitResult("corrected calibration value-sum is %s", [$sum2], ' cp')
        );

    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
