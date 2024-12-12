<?php

namespace Bizbozo\AdventOfCode\Year2024\Day11;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;

class Solution implements SolutionInterface
{

    private array $cache;

    public function getTitle(): string
    {
        return "Plutonian Pebbles";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $stones = Parser::numbers($inputStream);
        $stones = array_combine($stones, array_fill(0, count($stones), 1));

        $stones = $this->passTurns(25, $stones);
        $sum = array_sum($stones);
        $stones = $this->passTurns(50, $stones);
        $sum2 = array_sum($stones);

        return new SolutionResult(
            11,
            new UnitResult("After 25 blinks I've got %s stones.", [$sum]),
            new UnitResult("After 75 blinks I've got %s stones.", [$sum2])
        );
    }

    private function mogrify(int $stone): array
    {
        if (isset($this->cache[$stone])) {
            return $this->cache[$stone];
        }
        $ns = [];
        $len = strlen($stone);
        if ($stone == 0) {
            $ns[] = 1;
        } elseif (!($len % 2)) {
            $ns[] = intval(substr($stone, 0, $len >> 1));
            $ns[] = intval(substr($stone, $len >> 1));
        } else {
            $ns[] = 2024 * $stone;
        }
        $this->cache[$stone] = $ns;
        return $ns;
    }

    public function passTurns(int $steps, array $stones): array
    {
        for ($i = 0; $i < $steps; $i++) {
            $newStoneList = [];
            foreach ($stones as $stone => $amount) {
                $newStones = $this->mogrify($stone);
                foreach ($newStones as $newStone) {
                    $newStoneList[$newStone] = ($newStoneList[$newStone] ?? 0) + $amount;
                }
            }
            $stones = $newStoneList;
        }

        return $stones;
    }
}
