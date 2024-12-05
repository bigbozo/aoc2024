<?php

namespace Bizbozo\AdventOfCode\Year2023\Day02;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{
    #[\Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $games = static::parseData(explode(PHP_EOL, $inputStream));

        $setPowerSum = 0;
        $score = 0;
        foreach ($games as $id => $game) {
            /** @var Game $game */
            // named arguments for clearer function call ;)
            if ($game->isValid(red: 12, green: 13, blue: 14)) {
                $score += $id;
            }
            // use what is already there
            $setPowerSum += $game->getMinCubes()->power();
        }

        return new SolutionResult(
            2,
            new UnitResult("Score", $score, 'points'),
            new UnitResult("Set-Power-Sum", $setPowerSum, 'powerpoints')
        );

    }

    /**
     * Parse the data into games
     */
    public static function parseData($stream): array
    {
        $games = [];
        foreach ($stream as $line) {
            if (preg_match('/Game\s(\d+):\s(.*)$/', rtrim($line), $match)) {
                // match: 1=> Game-ID, 2=> subsets
                $games[$match[1]] = new Game(self::parseSubsets($match[2]));
            }
        }
        return $games;
    }

    /**
     * Parse the subsets string into an array of CubeDraw
     */
    private static function parseSubsets(string $subsetsString): array
    {
        $subsets = explode('; ', $subsetsString);
        return array_reduce($subsets, function ($carry, $subset) {
            $carry[] = CubeDraw::fromTest(self::parseDraw($subset));
            return $carry;
        }, []);
    }

    /**
     * Parse the draw string into an associative array
     */
    private static function parseDraw(string $drawString): array
    {
        $draw = [];
        $colorCounts = explode(', ', $drawString);
        foreach ($colorCounts as $colorCount) {
            [$count, $color] = explode(' ', $colorCount);
            $draw[$color] = $count;
        }
        return $draw;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
