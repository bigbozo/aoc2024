<?php

namespace Bizbozo\AdventOfCode\Structures;

class Board
{

    private array $board;
    public int $width;
    public int $height;

    public function __construct($stream)
    {
        $this->board = array_map(
            function ($line) {
                return array_map(
                    fn($char) => ['tile' => $char],
                    str_split($line)
                );
            },
            explode(PHP_EOL, $stream)
        );
        $this->width = count($this->board[0]);
        $this->height = count($this->board);
    }

    public function print(): void
    {
        echo implode(PHP_EOL, array_map(function ($row) {
            return implode('', $row);
        }, $this->board));
    }

    public function get(int $x, int $y): ?array
    {
        return $this->board[$y][$x] ?? null;
    }

    public function set(int $x, int $y, array $value): void
    {
        if ($x < 0 || $y < 0) return;
        if ($x >= $this->width || $y >= $this->height) return;
        $this->board[$y][$x] = $value;
    }


}