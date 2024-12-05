<?php

namespace Bizbozo\AdventOfCode\Year2023\Day12;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{
    static $cache = [];
    private static $resultFile = __DIR__ . '/../../output/day12-cached.txt';

    /**
     * @param array $lines
     * @return array{pattern: string[], numbers: int[][]}
     */
    private static function parseData(array $lines): array
    {
        $data = [];
        foreach ($lines as $line) {
            if (!trim($line)) continue;
            list($pattern, $numbers) = explode(" ", $line, 2);
            $numbers = array_map(fn($n) => (int)$n, explode(',', $numbers));
            $data[] = compact('pattern', 'numbers');
        }
        return $data;
    }

    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $records = static::parseData(explode(PHP_EOL, $inputStream));

        $sum = array_sum(array_map(function ($record) use ($records) {
            return static::countVariants($record['pattern'], $record['numbers']);
        }, $records));

        $sum2 = array_sum(array_map(function ($record) use ($records) {
            return static::countVariants(...static::foldOut($record['pattern'], $record['numbers']));
        }, $records));


        return new SolutionResult(
            12,
            new UnitResult('', $sum, ''),
            new UnitResult('', $sum2, '')
        );
    }

    private static function countVariants(mixed $pattern, mixed $numbers): int
    {

        $key = self::getKey($pattern, $numbers);
        if (isset(static::$cache[$key])) return static::$cache[$key];
        $reverseKey = static::reverseKey($pattern, $numbers);
        if (isset(static::$cache[$reverseKey])) return static::$cache[$reverseKey];

        if (!count($numbers)) {
            if (str_contains($pattern, '#')) {
                $counter = 0;
            } else {
                $counter = 1;
            }
        } else {

            $counter = 0;
            $nextChar = substr($pattern, 0, 1);
            if ($nextChar === '.' || $nextChar === '?') {
                $counter += self::countVariants(substr($pattern, 1), $numbers);
            }
            if (($nextChar === '#' || $nextChar === '?')
                && strlen($pattern) >= $numbers[0]
                && !str_contains(substr($pattern, 0, $numbers[0]), '.')
                && (strlen($pattern) == $numbers[0] || substr($pattern, $numbers[0], 1) != '#')
            ) {
                $counter += self::countVariants(substr($pattern, $numbers[0] + 1), array_slice($numbers, 1));
            }
        }

        static::$cache[$key] = $counter;

        return $counter;
    }

    private static function needed($numbers)
    {
        return array_sum($numbers) + count($numbers) - 1;
    }

    private static function foldOut(string $pattern, array $numbers)
    {
        return [
            implode("?", array_fill(0, 5, $pattern)),
            array_merge($numbers, $numbers, $numbers, $numbers, $numbers)
        ];

    }

    /**
     * @param mixed $pattern
     * @param mixed $numbers
     * @return string
     */
    private static function getKey(mixed $pattern, mixed $numbers): string
    {
        $key = md5($pattern . '-' . implode('-', $numbers));
        return $key;
    }

    private static function reverseKey(string $pattern, array $numbers): string
    {
        $key = md5(strrev($pattern) . '-' . strrev(implode('-', $numbers)));
        return $key;
    }

    private static function getCachedResults()
    {
        $cachedResults = [];

        if (file_exists(static::$resultFile)) {
            $lines = file_get_contents(static::$resultFile);
            foreach (explode(PHP_EOL, $lines) as $line) {
                if (trim($line)) {
                    list($key, $val) = explode(":", $line);
                    $cachedResults[$key] = $val;
                }
            }
        }
        return $cachedResults;
    }

    private static function cacheResult(string $key, int $result)
    {

        file_put_contents(static::$resultFile, "$key:$result" . PHP_EOL, FILE_APPEND);
    }


    public function getTitle(): string
    {
        return 'tbd.';
    }
}
