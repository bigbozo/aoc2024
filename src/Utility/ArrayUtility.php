<?php

namespace Bizbozo\AdventOfCode\Utility;

use Closure;

class ArrayUtility
{

    public static function partition(array $array, Closure $partitionFunction)
    {
        $result = [];
        foreach ($array as $cell) {
            $result[$partitionFunction($cell)][] = $cell;
        }
        return $result;
    }

}