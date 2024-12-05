<?php

namespace Bizbozo\AdventOfCode\Year2023\Day07;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

    #[\Override] public function solve($inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = static::parseInput($inputStream);

        usort($data, fn($a, $b) => $a['score'] <=> $b['score']);
        $part1Score = self::calculateFinalScore($data);

        usort($data, fn($a, $b) => $a['jokerScore'] <=> $b['jokerScore']);
        $part2Score = self::calculateFinalScore($data);

        return new SolutionResult(
            7,
            new UnitResult('score', $part1Score, 'pt'),
            new UnitResult('Joker-Score', $part2Score, 'pt')
        );
    }

    private static function parseInput($inputStream)
    {
        $lines = explode(PHP_EOL, $inputStream);
        foreach ($lines as $line) {
            if (!trim($line)) continue;
            list($hand, $bidding) = explode(" ", $line);
            $hand = str_split($hand);
            $hands[] = [
                'hand' => $hand,
                'bidding' => $bidding,
                'score' => static::scoreHand($hand),
                'jokerScore' => static::scoreHand($hand, 1)
            ];
        }
        return $hands;
    }

    private static function scoreHand($hand, $jokers = false)
    {
        $scores = array_flip(['11111', '2111', '221', '311', '32', '41', '5']);
        $cardValues = array_flip([2, 3, 4, 5, 6, 7, 8, 9, 'T', 'J', 'Q', 'K', 'A']);
        $jokerCardValues = array_flip(['J', 2, 3, 4, 5, 6, 7, 8, 9, 'T', 'Q', 'K', 'A']);
        $sortedHand = [];
        foreach ($hand as $card) {
            $sortedHand[$card] = isset($sortedHand[$card]) ? $sortedHand[$card] + 1 : 1;
        }
        arsort($sortedHand);
        if ($jokers) {
            if (isset($sortedHand['J'])) {
                $jokers = $sortedHand['J'];
                unset($sortedHand['J']);
                if (count($sortedHand)) {
                    $sortedHand[array_key_first($sortedHand)] += $jokers;
                } else {
                    $sortedHand['J'] = $jokers;
                }
            }
            arsort($sortedHand);
        }
        $score = $scores[implode('', $sortedHand)];
        foreach ($hand as $value) {
            $score <<= 4;
            $score += $jokers ? $jokerCardValues[$value] : $cardValues[$value];
        }
        return $score;
    }

    /**
     * @param array $data
     * @return float|int
     */
    private static function calculateFinalScore(array $data): int|float
    {
        $score = 0;
        foreach ($data as $rank => $hand) {
            $score += ($rank + 1) * $hand['bidding'];
        }
        return $score;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
