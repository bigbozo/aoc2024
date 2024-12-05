<?php

namespace Bizbozo\AdventOfCode\Year2023\Day05;

use Bizbozo\AdventOfCode\Year2023\Ranges\InvalidArgumentException;
use Bizbozo\AdventOfCode\Year2023\Ranges\Range;
use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

    /**
     * Solves the given problem.
     *
     * @param string $inputStream
     * @param string|null $inputStream2
     * @return SolutionResult The solution result containing the lowest location numbers.
     * @throws InvalidArgumentException
     */
    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        list($seeds, $steps) = static::parseInput($inputStream);

        // Part 1
        $locations = [];
        foreach ($seeds as $seed) {
            foreach ($steps as $step) {
                $seed = static::makeStep($seed, $step);
            }
            $locations[] = $seed;
        }

        // Part 2
        $seedIntervals = array_chunk($seeds, 2);
        $minLocations = [];
        foreach ($seedIntervals as $seedInterval) {
            $intervals = [
                new Range(
                    start: (int)$seedInterval[0],
                    end: (int)$seedInterval[0] + $seedInterval[1] - 1
                )
            ];
            foreach ($steps as $step) {
                $newIntervals = [];
                foreach ($step as $rule) {
                    $ruleInterval = new Range(
                        start: $rule['source'],
                        end: $rule['source'] + $rule['width'] - 1
                    );
                    for ($i = 0; $i < count($intervals); $i++) {
                        $interval = $intervals[$i];
                        if ($intersection = $interval->intersect($ruleInterval)) {
                            array_splice($intervals, $i--, 1);
                            $newIntervals[] = $intersection->shift($rule['offset']);
                            if ($differences = $interval->difference($intersection)) {
                                $intervals = array_merge($intervals, $differences);
                            }
                        }
                    }
                }
                $intervals = array_merge($intervals, $newIntervals);
            }
            $minLocation = min(array_column($intervals,'start'));
            $minLocations[] = $minLocation;

        }


        sort($minLocations);
        return new SolutionResult(
            5,
            new UnitResult('lowest location number', min($locations), 'loc'),
            new UnitResult('lowest location number', min($minLocations), 'loc')
        );
    }

    private static function parseInput($inputStream)
    {
        $parts = explode(PHP_EOL . PHP_EOL, $inputStream);
        $seeds = explode(' ', explode('seeds: ', $parts[0])[1]);
        $steps = [
            static::parseStep(explode('seed-to-soil map:' . PHP_EOL, $parts[1])),
            static::parseStep(explode('soil-to-fertilizer map:' . PHP_EOL, $parts[2])),
            static::parseStep(explode('fertilizer-to-water map:' . PHP_EOL, $parts[3])),
            static::parseStep(explode('water-to-light map:' . PHP_EOL, $parts[4])),
            static::parseStep(explode('light-to-temperature map:' . PHP_EOL, $parts[5])),
            static::parseStep(explode('temperature-to-humidity map:' . PHP_EOL, $parts[6])),
            static::parseStep(explode('humidity-to-location map:' . PHP_EOL, $parts[7])),
        ];

        return [$seeds, $steps];
    }

    private static function parseStep($stepString)
    {
        $rules = [];
        foreach (explode(PHP_EOL, $stepString[1]) as $step) {
            if ($step) {
                list($dest, $source, $width) = explode(' ', $step);
                $dest = (int)$dest;
                $source = (int)$source;
                $width = (int)$width;
                $offset = $dest - $source;
                $rules[] = compact('dest', 'source', 'width', 'offset');
            }
        }
        return $rules;
    }

    private static function makeStep($seed, $step)
    {
        foreach ($step as $rule) {
            if ($seed >= $rule['source'] && $seed < $rule['source'] + $rule['width']) {
                return $seed + $rule['dest'] - $rule['source'];
            }
        }
        return $seed;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
