<?php

namespace Bizbozo\AdventOfCode\Year2023\Day13;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    private static function parseData(string $lines): array
    {
        $boards = array_map(fn($board) => explode(PHP_EOL, $board), explode(PHP_EOL . PHP_EOL, $lines));

        return array_map(fn($board) => array_filter($board, fn($line) => trim($line)), $boards);

    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = static::parseData($inputStream);
        $sum = $sum2 = 0;
        foreach ($data as $board) {
            $score = static::calculateScore($board);
            $sum += $score;
            $score = static::calculateSmudgeScore($board, $score);
            $sum2 += $score;
        }

        return new SolutionResult(
            13,
            new UnitResult('Mirror value', $sum, ''),
            new UnitResult('Smudge mirror value', $sum2, '')
        );
    }

    private static function calculateScore(mixed $board, int $oldScore = -1): int
    {
        $width = strlen($board[0]);
        $height = count($board);
        $mirrorPositions = [];
        foreach ($board as $line) {
            for ($axis = 0; $axis < $width - 1; $axis++) {
                $left = $axis;
                $right = $axis + 1;
                while ($left >= 0 && $right < $width) {
                    if ($line[$left] != $line[$right]) {
                        continue 2;
                    }
                    $left--;
                    $right++;
                }
                $mirrorPositions[$axis] = ($mirrorPositions[$axis] ?? 0) + 1;
                if ($mirrorPositions[$axis] == $height) {
                    if ($axis + 1 != $oldScore) {
                        return $axis + 1;
                    }
                }
            }
        }
        $mirrorPositions = [];
        for ($x = 0; $x < $width; $x++) {
            for ($axis = 0; $axis < $height - 1; $axis++) {
                $left = $axis;
                $right = $axis + 1;
                while ($left >= 0 && $right < $height) {
                    if ($board[$left][$x] != $board[$right][$x]) {
                        continue 2;
                    }
                    $left--;
                    $right++;
                }
                $mirrorPositions[$axis] = ($mirrorPositions[$axis] ?? 0) + 1;
                if ($mirrorPositions[$axis] == $width) {
                    $score = 100 * ($axis + 1);
                    if ($score != $oldScore) {
                        return $score;
                    }
                }
            }
        }
        return -1;
    }

    private static function calculateSmudgeScore(array $board, $oldScore): int
    {

        $width = strlen($board[0]);
        $height = count($board);
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $old = substr($board[$y], $x, 1);
                $board[$y] = substr_replace($board[$y], $old == '.' ? '#' : '.', $x, 1);
                $score = static::calculateScore($board, $oldScore);
                if ($score > -1) {
                    if ($score != $oldScore) {
                        return $score;
                    }
                }
                $board[$y] = substr_replace($board[$y], $old, $x, 1);

            }
        }
        return 0;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
