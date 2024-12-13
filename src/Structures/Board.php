<?php

namespace Bizbozo\AdventOfCode\Structures;

use Closure;

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
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $this->board[$y][$x]['pos'] = [$x, $y];
                $this->board[$y][$x]['id'] = $x + $this->width * $y;
            }
        }

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

    public function each(Closure $closure): void
    {
        $this->board = array_map(fn($row) => array_map($closure, $row), $this->board);
    }

    public function find(Closure $filter): array
    {
        return array_filter($this->all(), $filter);
    }

    public function first(Closure $filter, ?array $start = null): ?array
    {
        // has to be optimized; speed gain in production from 1.500ms -> 93
        if ($start) {
            $offset = $start['pos'][0] + $this->width * $start['pos'][1];
        } else {
            $offset = 0;
        }
        $size = $this->width * $this->height;
        for ($i = $offset; $i < $size; $i++) {
            $x = $i % $this->width;
            $y = intdiv($i, $this->width);
            if ($filter($this->board[$y][$x])) {
                return $this->board[$y][$x];
            }
        }
        return null;
    }

    /**
     * returns an array with horizontal and vertical neighbours
     */
    public function findNeighbours(array $position): array
    {
        $dirs = [[1, 0], [0, 1], [-1, 0], [0, -1]];

        $cells = [];
        foreach ($dirs as $dir) {
            if ($cell = $this->get($position[0] + $dir[0], $position[1] + $dir[1])) {

                $cells[] = $cell;

            }
        }

        return $cells;
    }

    public function update(mixed $pos, Closure|array|null $param = null): bool
    {
        if (is_null($param)) {
            $this->set($pos['pos'][0], $pos['pos'][1], $pos);
            return true;
        }
        $cell = $this->board[$pos[1]][$pos[1]] ?? null;
        if ($cell) {
            if ($param instanceof Closure)
                $this->set($pos[0], $pos[1], $param($cell));
            else
                $this->set($pos[0], $pos[1], $param);
            return true;
        }
        return false;
    }

    /**
     * @return array|array[]|\array[][]
     */
    public function all(): array
    {
        return array_merge(...$this->board);
    }

    public function partition(Closure $partitionFunction)
    {
        $result = [];
        foreach ($this->all() as $cell) {
            $result[$partitionFunction($cell)][] = $cell;
        }
        return $result;
    }

}