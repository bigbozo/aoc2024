<?php

namespace Bizbozo\AdventOfCode\Utility;

class Parser
{
    public static function lines(string $stream): array
    {
        return array_filter(explode(PHP_EOL, $stream), 'trim');
    }

    public static function numbers($stream): array
    {

        return array_map('intval', explode(' ', $stream));
    }

    public static function numChars(string $inputStream)
    {
        return array_map('intval', str_split($inputStream));
    }

    public static function blocks(string $inputStream)
    {
        return explode(PHP_EOL . PHP_EOL, trim($inputStream));
    }

}