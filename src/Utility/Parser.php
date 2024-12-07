<?php

namespace Bizbozo\AdventOfCode\Utility;

class Parser
{
    public static function lines(string $stream): array
    {
        return array_filter(explode(PHP_EOL, $stream), 'trim');
    }

}