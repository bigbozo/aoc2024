<?php

namespace Bizbozo\AdventOfCode\Year2023\Day08;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use function PHPUnit\Framework\stringEndsWith;

class Solution implements SolutionInterface
{

    private static function parseData(array $lines)
    {
        $instructions = array_shift($lines);
        array_shift($lines);
        $nodes = [];
        $startNodes = [];
        foreach ($lines as $line) {
            if (preg_match('/(\w+) = \((\w+), (\w+)\)/', $line, $match)) {
                $nodes[$match[1]] = ['L' => $match[2], 'R' => $match[3]];
                if (str_ends_with($match[1], 'A')) {
                    $startNodes[] = $match[1];
                }
            }

        }
        return [$instructions, $nodes, $startNodes];

    }

    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {


        list($instructions, $nodes, $startNodes) = static::parseData(explode(PHP_EOL, $inputStream));
        $instructionLength = strlen($instructions);

        $steps1 = 0;
        $node = 'AAA';
        do {
            $node = $nodes[$node][$instructions[$steps1 % $instructionLength]];
            $steps1++;
        } while ($node !== 'ZZZ');

        if ($inputStream2) {
            list($instructions, $nodes, $startNodes) = static::parseData(explode(PHP_EOL, $inputStream2));
            $instructionLength = strlen($instructions);
        }
        $results = [];
        foreach ($startNodes as &$startNode) {
            $steps2 = 0;
            do {
                $instruction = $instructions[$steps2 % $instructionLength];
                $startNode = $nodes[$startNode][$instruction];
                $steps2++;
                if (!($steps2 % 1E7)) {
                    echo '.';
                }
            } while (!str_ends_with($startNode, 'Z'));
            $results[] = $steps2;
        }



        return new SolutionResult(
            8,
            new UnitResult('number of steps', $steps1, 'steps'),
            new UnitResult('number of parallel steps', static::smallectCommonDenominator($results), 'psteps')
        );
    }

    private static function notAtTarget(mixed $startNodes)
    {
        return array_filter($startNodes, fn($n) => !str_ends_with($n, 'Z'));
    }

    private static function primeFactors($number)
    {
        $factors = [];
        // max. Primfaktor ist Wurzel der Zahl
        for ($i = 2; $i <= sqrt($number); $i++) {
            while ($number % $i === 0) {
                $factors[$i] = ($factors[$i] ?? 0) + 1;
                $number /= $i;
            }
        }
        if ($number > 1) {
            $factors[$number] = ($factors[$number] ?? 0) + 1;
        }
        return $factors;
    }

    private static function smallectCommonDenominator(array $numbers)
    {
        $factors = ['1' => 1];
        foreach ($numbers as $number) {
            foreach (static::primeFactors($number) as $factor => $count) {
                $factors[$factor] = max($factors[$factor] ?? 0, $count);
            };
        }
        return array_product(array_map(function ($factor, $count) {
            return pow($factor, $count);
        }, array_keys($factors), array_values($factors)));
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
