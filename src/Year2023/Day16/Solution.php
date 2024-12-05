<?php

namespace Bizbozo\AdventOfCode\Year2023\Day16;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

enum Direction
{
    case NORTH;
    case EAST;
    case SOUTH;
    case WEST;
}

enum Tile
{
    case EMPTY;
    case MIRROR_SWNE;
    case MIRROR_NWSE;
    case SPLITTER_HORIZONTAL;
    case SPLITTER_VERTICAL;

    /**
     * @param Direction $dir
     * @return Direction[]
     * @throws \Exception
     */
    function from(Direction $dir): array
    {
        return match ($this) {
            self::EMPTY => match ($dir) {
                Direction::NORTH => [Direction::SOUTH],
                Direction::EAST => [Direction::WEST],
                Direction::SOUTH => [Direction::NORTH],
                Direction::WEST => [Direction::EAST],
            },
            self::MIRROR_SWNE => match ($dir) {
                Direction::NORTH => [Direction::WEST],
                Direction::EAST => [Direction::SOUTH],
                Direction::SOUTH => [Direction::EAST],
                Direction::WEST => [Direction::NORTH],
            },
            self::MIRROR_NWSE => match ($dir) {
                Direction::NORTH => [Direction::EAST],
                Direction::EAST => [Direction::NORTH],
                Direction::SOUTH => [Direction::WEST],
                Direction::WEST => [Direction::SOUTH],
            },
            self::SPLITTER_HORIZONTAL => match ($dir) {
                Direction::NORTH => [Direction::WEST, Direction::EAST],
                Direction::EAST => [Direction::WEST],
                Direction::SOUTH => [Direction::WEST, Direction::EAST],
                Direction::WEST => [Direction::EAST],

            },
            self::SPLITTER_VERTICAL => match ($dir) {
                Direction::NORTH => [Direction::SOUTH],
                Direction::EAST => [Direction::SOUTH, Direction::NORTH],
                Direction::SOUTH => [Direction::NORTH],
                Direction::WEST => [Direction::NORTH, Direction::SOUTH],
            },

        };
    }

    public function print()
    {
        return match ($this) {
            self::EMPTY => '.',
            self::MIRROR_SWNE => '/',
            self::MIRROR_NWSE => '\\',
            self::SPLITTER_HORIZONTAL => '-',
            self::SPLITTER_VERTICAL => '|',
        };
    }
}

class Cell
{
    public function __construct(public Tile $tile, public array $visitsFrom = [])
    {
    }

    public function visitAndCheck(Direction $from): bool
    {
        $alreadyVisited = false;
        if (isset($this->visitsFrom[$from->name])) $alreadyVisited = true;
        $this->visitsFrom[$from->name] = true;
        return $alreadyVisited;
    }

    public function reset()
    {
        $this->visitsFrom = [];
    }

}

class Board
{

    /** @var Cell[] */
    private array $data;

    public function __construct(public int $width, public int $height)
    {
        $this->data = array_fill(0, $this->width, array_fill(0, $this->height, null));
    }

    public function get(int $x, int $y): Cell
    {
        $this->checkCoordinates($x, $y);
        return $this->data[$x][$y];
    }

    public function set(int $x, int $y, Cell $cell)
    {
        $this->checkCoordinates($x, $y);
        $this->data[$x][$y] = $cell;
    }

    public function reset()
    {
        foreach ($this->data as $row) {
            foreach ($row as $cell) {
                /** @var $cell Cell */
                $cell->reset();
            }
        }
    }

    public function checkCoordinates(int $x, int $y)
    {
        if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
            throw new \OutOfBoundsException("Coordinates ($x/$y) out of bounds");
        }
    }


    public function walk(Cursor $cursor): array
    {
        switch ($cursor->direction) {
            case Direction::NORTH:
                $cursor->y -= 1;
                $direction = Direction::SOUTH;
                break;
            case Direction::SOUTH:
                $cursor->y += 1;
                $direction = Direction::NORTH;
                break;
            case Direction::EAST:
                $cursor->x += 1;
                $direction = Direction::WEST;
                break;
            case Direction::WEST:
                $cursor->x -= 1;
                $direction = Direction::EAST;
                break;
        }
        try {
            $cell = $this->get($cursor->x, $cursor->y);
        } catch (\OutOfBoundsException $e) {
            return [];
        }
        if ($cell->visitAndCheck($direction)) {
            return [];
        }
        $cursors = [];
        foreach ($cell->tile->from($direction) as $newDirection) {
            $cursors[] = new Cursor($cursor->x, $cursor->y, $newDirection);
        }

        return $cursors;
    }

    public function countVisited()
    {
        $count = 0;
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                $cell = $this->get($x, $y);
                if (count($cell->visitsFrom) > 0) $count++;
            }
        }
        return $count;
    }

    public function print()
    {
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $cell = $this->get($x, $y);
                if ($cell->tile == Tile::EMPTY && count($cell->visitsFrom) > 0) {
                    echo '#';
                } else {
                    echo $cell->tile->print();
                }
            }
            echo PHP_EOL;
        }
    }
}

class Cursor
{
    public function __construct(public int $x, public int $y, public Direction $direction)
    {
    }
}


class Solution implements SolutionInterface
{

    private static function parseData(array $lines): Board
    {
        $lines = array_filter($lines, fn($line) => strlen($line));
        $board = new Board(strlen($lines[0]), count($lines));

        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $cell) {
                $board->set($x, $y, new Cell(match ($cell) {
                    "." => Tile::EMPTY,
                    "|" => Tile::SPLITTER_VERTICAL,
                    '-' => Tile::SPLITTER_HORIZONTAL,
                    '/' => Tile::MIRROR_SWNE,
                    '\\' => Tile::MIRROR_NWSE
                }));
            }
        }
        return $board;
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $board = static::parseData(explode(PHP_EOL, $inputStream));

        $cursors = [new Cursor(-1, 0, Direction::EAST)];
        $result1 = self::simulate($cursors, $board);
        $board->reset();

        $result2 = 0;

        for ($x = 0; $x < $board->width; $x++) {
            $cursors = [new Cursor($x, -1, Direction::SOUTH)];
            $result2 = max($result2, self::simulate($cursors, $board));
            $board->reset();
            $cursors = [new Cursor($x, $board->height, Direction::NORTH)];
            $result2 = max($result2, self::simulate($cursors, $board));
            $board->reset();
        }
        for ($y = 0; $y < $board->height; $y++) {
            $cursors = [new Cursor(-1, $y, Direction::EAST)];
            $result2 = max($result2, self::simulate($cursors, $board));
            $board->reset();
            $cursors = [new Cursor($board->width, $y, Direction::WEST)];
            $result2 = max($result2, self::simulate($cursors, $board));
            $board->reset();
        }


        return new SolutionResult(
            16,
            new UnitResult('number of energized Tiles ', $result1, 'tiles'),
            new UnitResult('optimized number of energized Tiles ', $result2, 'tiles'),
        );
    }

    /**
     * @param array $cursors
     * @param Board $board
     * @return int
     */
    private static function simulate(array $cursors, Board $board): int
    {
        while (count($cursors)) {
            $newCursors = [];
            foreach ($cursors as $cursor) {
                $newCursors = array_merge($newCursors, $board->walk($cursor));
            }
            $cursors = $newCursors;
        }
        $amount = $board->countVisited();
        return $amount;
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
