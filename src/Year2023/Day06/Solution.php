<?php

namespace Bizbozo\AdventOfCode\Year2023\Day06;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        list($times, $distances) = static::parseInput($inputStream);

        $solution_part1 = array_product(self::solveRaces($times, $distances));
        $solution_part2 = self::solveRace(implode('', $distances), implode('', $times));

        return new SolutionResult(
            6,
            new UnitResult('', $solution_part1, ''),
            new UnitResult('', $solution_part2, '')
        );
    }

    /**
     * parses the input string with newlines
     * @param $inputStream
     * @return array
     */
    private static function parseInput($inputStream)
    {
        list($timeString, $distanceString) = explode(PHP_EOL, $inputStream, 3);
        return [
            self::parseLine($timeString, "Time"),
            self::parseLine($distanceString, "Distance")
        ];
    }

    /**
     * parses one line of input
     * @param string $string
     * @param string $legend
     * @return int[]
     */
    private static function parseLine(string $string, string $legend): array
    {
        return array_map(
            fn($t) => (int)$t,
            array_filter(
                preg_split('/\s+/', substr($string, strlen($legend . ':'))),
                fn($item) => is_numeric($item)
            ));
    }

    /**
     * Solves a list of races and returns the solutions
     * @param int[] $times
     * @param int[] $distances
     * @return array
     */
    private static function solveRaces(array $times, array $distances): array
    {
        $solutions = [];
        foreach ($times as $id => $time) {
            $solutions[] = self::solveRace($distances[$id], $time);
        }
        return $solutions;
    }

    /**
     * Solves a single race
     * @param int $distances
     * @param int $time
     * @return int
     */
    private static function solveRace(int $distance, int $time): int
    {
        // https://www.wolframalpha.com/input?i=%28a+-+x%29x+%3E+b
        $left = 1 / 2 * ($time - sqrt($time * $time - 4 * $distance));
        $right = 1 / 2 * ($time + sqrt($time * $time - 4 * $distance));
        return self::rightExclusiveInt($right) - self::leftExlusiveInt($left) + 1;
    }

    /**
     * round ints up
     * @param float|int $left
     * @return float|int
     */
    private static function leftExlusiveInt(float|int $left): int|float
    {
        return (ceil($left) != $left) ? ceil($left) : $left + 1;
    }

    /**
     * round ints down
     *
     * @param float|int $right
     * @return float|int
     */
    private static function rightExclusiveInt(float|int $right): int
    {
        if (floor($right) != $right) {
            $right = floor($right);
        } else {
            $right = $right - 1;
        }
        return (int)$right;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
