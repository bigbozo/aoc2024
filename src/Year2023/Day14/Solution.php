<?php

namespace Bizbozo\AdventOfCode\Year2023\Day14;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        $map = self::parseMap($inputStream);
        // Part 1
        $map = self::gravity($map);
        $sum1 = self::score($map);

        // reinitialize for Part 2
//        $map = self::parseMap($inputStream);
        $cycle = 0;
        $cycles = 1_000_000_000;
        $period = 0;
        $cache[self::mapKey($map)] = 0;
        while ($cycle < $cycles) {
            for ($i = 0; $i < 4; $i++) {
                $map = self::gravity($map);
                $map = self::rotateMap($map);
            }
            $sum2 = self::score($map);
            $cycle++;


            $mapKey = self::mapKey($map);
            if (!$period && $cycle && isset($cache[$mapKey]) && $cache[$mapKey]) {
                $period = $cycle - $cache[$mapKey];
                $cycle = $cycles - ($cycles - $cycle) % $period;
            }

            $cache[$mapKey] = $cycle;

        }

        // 105664 is too high
        return new SolutionResult(
            14,
            new UnitResult('', $sum1, ' (Test: 136)'),
            new UnitResult('', $sum2, '')
        );
    }

    /**
     * @param array $map
     * @return array
     */
    private static function gravity(array $map): array
    {
        $width = count($map[0]);
        $height = count($map);
        $newMap = array_fill(0, $height, array_fill(0, $width, '.'));
        $max = array_fill(0, $width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($map[$y][$x] == '#') {
                    $max[$x] = $height - $y - 1;
                    $newMap[$y][$x] = '#';
                } elseif ($map[$y][$x] == 'O') {
                    $newMap[$height - $max[$x]][$x] = 'O';
                    $max[$x]--;
                }
            }
        }
        return $newMap;
    }

    /**
     * @param $map
     * @return void
     */
    private static function printMap($map): string
    {
        return implode(PHP_EOL, array_map(fn($line) => implode("", $line), $map)) . PHP_EOL;
    }


    private static function rotateMap(array $map): array
    {
        $height = count($map[0]);
        $width = count($map);
        $newMap = array_fill(0, $width, array_fill(0, $height, '.'));
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $newMap[$x][$height - 1 - $y] = $map[$y][$x];
            }
        }
        return $newMap;
    }

    private static function mapKey(array $map)
    {
        return md5(self::printMap($map));
    }

    /**
     * @param string $inputStream
     * @return array|array[]
     */
    private static function parseMap(string $inputStream): array
    {
        $map = array_map(
            fn($line) => str_split($line),
            array_filter(explode(PHP_EOL, $inputStream), fn($line) => trim($line))
        );
        return $map;
    }

    private static function score(array $map): int
    {
        $height = count($map);
        $score = 0;
        foreach ($map as $y => $line) {
            foreach ($line as $x => $item) {
                if ($item == 'O') $score += $height - $y;
            }
        }
        return $score;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
