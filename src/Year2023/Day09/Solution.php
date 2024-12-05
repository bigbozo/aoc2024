<?php

namespace Bizbozo\AdventOfCode\Year2023\Day09;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use OutOfRangeException;

/***
 * This class implements the SolutionInterface and provides a method to solve day 09
 */
class Solution implements SolutionInterface
{

    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = static::parseData(explode(PHP_EOL, $inputStream));

        $sum = $sumLeft = 0;
        foreach ($data as $series) {
            $sum += static::resolve($series);
            $sumLeft += static::resolveLeft($series);
        }

        return new SolutionResult(
            9,
            new UnitResult('Oasis And Sand Instability Sensor reading sum', $sum, 'oa'),
            new UnitResult('sum of the extrapolated OASIS values', $sumLeft, '')
        );
    }

    /**
     * Parses an array of lines into a two-dimensional array of integers.
     *
     * @param string[] $lines An array of lines
     * @return int[][] The parsed two-dimensional array containing integer values from the lines
     */
    private static function parseData(array $lines): array
    {
        return array_filter(array_map(
            fn($numbers) => array_map(fn($number) => (int)$number, $numbers),
            array_map(fn($line) => explode(' ', $line), $lines)
        ), fn($series) => count($series) > 1);
    }

    /**
     * Resolves an array of numbers by recursively reducing the array until all values are 0
     * to return the next value in the series
     *
     * @param int[] $series An array of numbers
     * @return int The resolved value
     * @throws OutOfRangeException If the array contains less than 2 numbers
     */
    private static function resolve(array $series): int
    {
        $derived = static::reduce($series);
        if (!static::isResolved($derived)) {
            $value = static::resolve($derived) + $series[array_key_last($series)];
        } else {
            return $series[array_key_last($series)];
        }
        return $value;
    }

    /**
     * Resolves an array of numbers by recursively reducing the array until all values are 0
     * to return the preceding value of the series.
     *
     * @param mixed $series The series to resolve the leftmost value from.
     * @return int The resolved leftmost value.
     */
    private static function resolveLeft(mixed $series): int
    {
        $derived = static::reduce($series);
        if (!static::isResolved($derived)) {
            $value = $series[array_key_first($series)] - static::resolveLeft($derived);
        } else {
            return $series[array_key_first($series)];
        }
        return $value;
    }

    /**
     * Reduces an array of numbers by computing the difference between each consecutive pair of numbers.
     *
     * @param int[] $numbers An array of numbers
     * @return int[] The reduced array which contains the differences between each consecutive pair of numbers
     * @throws OutOfRangeException If the array contains less than 2 numbers
     */
    private static function reduce(array $numbers)
    {
        if (count($numbers) < 2) {
            throw new OutOfRangeException('measure out of range');
        }
        $reduced = [];
        for ($i = 1; $i < count($numbers); $i++) {
            $reduced[] = $numbers[$i] - $numbers[$i - 1];
        }
        return $reduced;
    }

    /**
     * Checks if an array of numbers is resolved, i.e. if all the numbers in the array are zero.
     *
     * @param int[] $numbers An array of numbers to be checked
     * @return bool Returns true if all the numbers in the array are zero, false otherwise
     */
    private static function isResolved(array $numbers)
    {
        return count(array_filter($numbers, fn($number) => $number !== 0)) == 0;
    }


    public function getTitle(): string
    {
        return 'tbd.';
    }
}
