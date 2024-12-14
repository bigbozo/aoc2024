<?php

namespace Bizbozo\AdventOfCode\Year2024\Day13;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;

class Solution implements SolutionInterface
{


    public function getTitle(): string
    {
        return "Claw Contraption";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $games = array_map(
            fn($block) => Parser::lines($block),
            Parser::blocks($inputStream)
        );

        $invest = 0;
        foreach ($games as $game) {
            list($buttonA, $buttonB, $prize) = $this->parseBlock($game);
            $solution = $this->getSolution($prize, $buttonA, $buttonB);
            if ($solution) {
                $invest += $solution[2];
            }
        }

        $invest2 = 0;
        foreach ($games as $game) {
            list($buttonA, $buttonB, $prize) = $this->parseBlock($game);
            $prize[0] += 10000000000000;
            $prize[1] += 10000000000000;
            $solution = $this->getSolution($prize, $buttonA, $buttonB, false);
            if ($solution) {
                $invest2 += $solution[2];
            }
        }

        return new SolutionResult(
            13,
            new UnitResult("The 1st answer is %s", [$invest]),
            new UnitResult('The 2nd answer is %s', [$invest2])
        );
    }

    public function parseBlock(mixed $game): array
    {
        preg_match('/Button A: X\+(\d+), Y\+(\d+)/', $game[0], $buttonA);
        preg_match('/Button B: X\+(\d+), Y\+(\d+)/', $game[1], $buttonB);
        preg_match('/Prize: X=(\d+), Y=(\d+)/', $game[2], $prize);
        $buttons = [
            [(int)$buttonA[1], (int)$buttonA[2]],
            [(int)$buttonB[1], (int)$buttonB[2]],
        ];
        $prize = [(int)$prize[1], (int)$prize[2]];
        return array($buttons[0], $buttons[1], $prize);
    }

    /**
     * @param mixed $prize
     * @param mixed $buttonA
     * @param mixed $buttonB
     * @return array
     */
    public function getSolution(mixed $prize, mixed $buttonA, mixed $buttonB, $maxPresses = true): array
    {
        $solution = [];
        $pressA = round(($prize[1] - $buttonB[1] / $buttonB[0] * $prize[0]) / ($buttonA[1] - ($buttonA[0] * $buttonB[1]) / $buttonB[0]));
        if ($maxPresses) $pressA = min(100, $pressA);
        $x = $prize[0] - $buttonA[0] * $pressA;
        $y = $prize[1] - $buttonA[1] * $pressA;
        if (!($x % $buttonB[0])) {
            $pressB = $x / $buttonB[0];
            if ($y - $pressB * $buttonB[1] == 0 && ($pressB <= 100 || !$maxPresses)) {
                // solution found
                $price = 3 * $pressA + $pressB;
                if (!$solution || $solution[2] > $price) {
                    $solution = [
                        $pressA, $pressB, $price
                    ];
                }
            }
        }
        return $solution;
    }
}
