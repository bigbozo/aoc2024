<?php

namespace Bizbozo\AdventOfCode\Year2023\Day15;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    private static function parseData(string $line): array
    {
        return explode(',', chop($line));
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $instructions = static::parseData($inputStream);
        $result = 0;
        $boxes = [];
        foreach ($instructions as $instruction) {
            $hash = self::hash($instruction);
            $result += $hash;
            if (str_ends_with($instruction,'-')) {
                $label = substr($instruction,0,-1);
                $boxId = self::hash($label);
                unset($boxes[$boxId][$label]);
            } else {
                list($label,$lens)=explode('=',$instruction);
                $boxId = self::hash($label);
                $boxes[$boxId][$label]=$lens;
            }

        }
        $result2 = 0;
        foreach ($boxes as $boxId => $box) {
            $slot = 0;
            $subResult=0;
            if (count($box)) {
                foreach ($box as $lens) {
                    $slot++;
                    $f = ($boxId + 1) * $slot * $lens;
                    $subResult += $f;
                }
                $result2 += $subResult;
            }
        }


        return new SolutionResult(
            15,
            new UnitResult('sum of results', $result, ''),
            new UnitResult('focusing power of lens configuration', $result2, '')
        );
    }

    private static function hash(mixed $instruction)
    {
        $current = 0;
        for ($i = 0; $i < strlen($instruction); $i++) {
            $char = $instruction[$i];
            $current = (($current + ord($char)) * 17) & 0xff;
        }
        return $current;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
