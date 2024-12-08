<?php

namespace Bizbozo\AdventOfCode\Structures;

class Board
{

    public array $board;
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
            array_filter(explode(PHP_EOL, $stream), 'trim')
        );
        $this->width = count($this->board[0]);
        $this->height = count($this->board);
    }

    public function print(): void
    {
        echo implode(PHP_EOL, array_map(function ($row) {
                return implode('', array_map(fn($cell) => $cell['tile'], $row));
            }, $this->board)) . PHP_EOL;
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

    public function setWithResponse(int $x, int $y, array $value): bool
    {
        if ($x < 0 || $y < 0) return false;
        if ($x >= $this->width || $y >= $this->height) return false;
        $this->board[$y][$x] = $value;
        return true;
    }

    public function each(\Closure $closure): void
    {
        $this->board = array_map(fn($row) => array_map($closure, $row), $this->board);
    }


}