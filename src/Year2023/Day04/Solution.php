<?php

namespace Bizbozo\AdventOfCode\Year2023\Day04;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{


    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        $data = static::parseInput(explode(PHP_EOL, $inputStream));

        $score = 0;
        $copies = array_fill(0, count($data), 1);
        foreach ($data as $card_number => $card) {
            $count = count(array_intersect($card['win'], $card['test']));
            $num_cards = $copies[$card_number];
            if ($count > 0) {
                $score += pow(2, $count - 1);
                for ($i = 0; $i < $count; $i++) {
                    $copies[$card_number + $i + 1] = ($copies[$card_number + $i + 1] ?? 0) + $num_cards;
                }
            }
        }

        return new SolutionResult(
            4,
            new UnitResult('Points', $score, 'pt'),
            new UnitResult('Card-Count', array_sum($copies), 'c')
        );
    }

    private static function parseInput($inputStream)
    {
        $data = [];
        foreach ($inputStream as $line) {
            if (!trim($line)) {
                continue;
            }
            list($card, $numbers) = explode(': ', chop($line));
            list($winNumbers, $testNumbers) = explode('| ', $numbers);
            $data[] = [
                'win' => preg_split('/\s+/', trim($winNumbers)),
                'test' => preg_split('/\s+/', trim($testNumbers)),
            ];
        }
        return $data;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
