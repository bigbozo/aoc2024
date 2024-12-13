<?php

namespace Bizbozo\AdventOfCode\Year2024\Day07;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;

class Solution implements SolutionInterface
{

    public function getTitle(): string
    {
        return "Bridge repair";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = array_map(function ($line) {
            [$key, $values] = explode(': ', $line);
            return [
                (int)$key,
                Parser::numbers($values)
            ];
        }, Parser::lines($inputStream));
        $score = 0;
        $unsolved = [];
        foreach ($data as $datum) {
            $result = $datum[0];
            $numbers = $datum[1];
            $found = false;
            $variants = pow(2, count($numbers) - 1) - 1;
            for ($i = 0; $i <= $variants; $i++) {
                if ($this->check($numbers, $result, $i)) {
                    $score += $result;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $unsolved[] = $datum;
            }
        }
        $score2=$score;
        foreach ($unsolved as $datum) {
            $result = $datum[0];
            $numbers = $datum[1];
            $found = false;
            $variants = pow(3, count($numbers) - 1) - 1;
            for ($i = 0; $i <= $variants; $i++) {
                if ($this->check2($numbers, $result, $i)) {
                    $score2 += $result;
                    break;
                }
            }
        }

        return new SolutionResult(
            7,
            new UnitResult("The total calibration result evaluates to %s", [$score]),
            new UnitResult('When considering the hiding spots of those gnarly elephants, the calibration result changes to %s', [$score2])
        );
    }

    private function check(array $numbers, int $result, int $variant): bool
    {
        $res = $numbers[0];
        for ($i = 1; $i < count($numbers); $i++) {
            if ($variant & 1) {
                $res += $numbers[$i];
            } else {
                $res *= $numbers[$i];
            }
            if ($res > $result) return false;
            $variant >>= 1;
        }
        return $res === $result;
    }

    private function check2(array $numbers, int $result, int $variant): bool
    {
        $res = $numbers[0];
        for ($i = 1; $i < count($numbers); $i++) {
            $operator = $variant % 3;
            switch ($operator) {
                case 0:
                    $res += $numbers[$i];
                    break;
                case 1:
                    $res *= $numbers[$i];
                    break;
                case 2:
                    $res = intval($res . $numbers[$i]);
                    break;

            }
            if ($res > $result) return false;
            $variant = intdiv($variant, 3);
        }
        return $res === $result;
    }
}
