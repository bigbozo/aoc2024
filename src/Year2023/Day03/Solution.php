<?php

namespace Bizbozo\AdventOfCode\Year2023\Day03;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = static::parseInput(explode(PHP_EOL,$inputStream));

        $part1sum = 0;
        $gear_numbers = [];

        foreach ($data['symbols'] as $symbol) {
            $symbol_numbers = [];
            static::calculateSymbolNumbers($symbol, $symbol_numbers, $data);
            $part1sum += array_sum($symbol_numbers);

            if ($symbol['char'] === '*' && count($symbol_numbers) === 2) {
                $gearCount = array_product($symbol_numbers);
                $gear_numbers[] = $gearCount;
            }
        }

        return new SolutionResult(3,
            new UnitResult('Partnumber-Sum', $part1sum, 'pns'),
            new UnitResult('Gear-Ratio-Sum', array_sum($gear_numbers), 'grs')
        );
    }

    private static function calculateSymbolNumbers($symbol, &$symbol_numbers, $data)
    {
            for ($x = -1; $x <= 1; $x++) {
                for ($y = -1; $y <= 1; $y++) {
                    $number_id = $data['field'][$x + $symbol['position']['x']][$y + $symbol['position']['y']] ?? -1;
                    if ($number_id > -1) {
                        $symbol_numbers[$number_id] = $data['numbers'][$number_id];
                    }
                }
            }
    }

    private static function parseInput($inputStream)
    {
        $numbers = [];
        $symbols = [];
        $field = [];
        $current_id = 0;
        foreach ($inputStream as $y => $line) {
            $line = chop($line) . 'Day03';
            $numStart = -1;
            for ($x = 0; $x < strlen($line); $x++) {
                $char = $line[$x];
                if ($numStart === -1) {
                    // Space
                    if ($char === '.') continue;
                    if (is_numeric($char)) {
                        // Digit
                        $numStart = $x;
                        $number = $char;
                    } else {
                        // Symbol
                        $symbols[] = [
                            'position' => ['x' => $x, 'y' => $y],
                            'char' => $char
                        ];
                    }
                } else {
                    if (is_numeric($char)) {
                        // Digit
                        $number .= $char;
                    } else {
                        // Space
                        $numbers[$current_id] = (int)$number;
                        for ($i = 1; $i <= strlen($number); $i++) {
                            $field[$x - $i][$y] = $current_id;
                        }
                        $current_id++;
                        $numStart = -1;
                        $number = '';
                        if ($char !== '.') {
                            // Symbol
                            $symbols[] = [
                                'position' => ['x' => $x, 'y' => $y],
                                'char' => $char
                            ];
                        }
                    }
                }
            }
        }

        return [
            'numbers' => $numbers,
            'symbols' => $symbols,
            'field' => $field,
        ];
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
