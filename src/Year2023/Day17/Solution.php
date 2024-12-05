<?php

namespace Bizbozo\AdventOfCode\Year2023\Day17;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

abstract class Direction
{
    protected string $key = '';

    public function __construct(public int $amplitude)
    {
    }

    public function __toString(): string
    {
        return $this->key . $this->amplitude;
    }

    public function next(): Direction
    {
        return new static($this->amplitude + 1);
    }

    public static function from(string $code): ?Direction
    {
        list($dir, $amp) = str_split($code);
        return match ($dir) {
            'N' => new North($amp),
            'W' => new West($amp),
            'S' => new South($amp),
            'E' => new East($amp)
        };
    }

}

class North extends Direction
{
    protected string $key = 'N';
}

class East extends Direction
{
    protected string $key = 'E';
}

class South extends Direction
{
    protected string $key = 'S';
}

class West extends Direction
{
    protected string $key = 'W';
}


class Node
{

    public function __construct(
        public int        $x,
        public int        $y,
        public ?Direction $direction,
        public int        $cost,
        public int        $distance = 100000000,
        public ?string    $from = null,
        public bool       $visited = false)
    {
    }

    public function key()
    {
        return sprintf("%s-%s-%s", $this->x, $this->y, $this->direction);
    }
}

class Solution implements SolutionInterface
{

    /**
     * @param array $lines
     * @return array{node:Node[], width: int, height: int}
     */
    private static function parseData(array $lines): array
    {
        $lines = array_filter($lines, fn($line) => trim($line));
        $nodes = [new Node(0, 0, null, 0, 0, 0)];
        $width = strlen($lines[0]);
        $height = count($lines);
        $allDirections = [
            new North(1), new North(2), new North(3),
            new West(1), new West(2), new West(3),
            new South(1), new South(2), new South(3),
            new East(1), new East(2), new East(3)
        ];

        foreach ($lines as $y => $line) {
            if (trim($line)) {
                foreach (str_split($line) as $x => $cost) {
                    if ($x !== 0 || $y !== 0) {
                        foreach ($allDirections as $direction) {
                            $nodes["$x-$y-$direction"] = new Node($x, $y, $direction, $cost);
                        }
                    }
                }
            }
        }
        return [$nodes, $width, $height];
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        /** @var Node[] $nodes */
        list($nodes, $width, $height) = static::parseData(explode(PHP_EOL, $inputStream));
        $currentNode = array_shift($nodes);
        $nodeDistances = [];
        do {
            $neighbours = [];
            if ($currentNode->x > 0 && !is_a($currentNode->direction, West::class)) {
                if ($currentNode->direction != new East(3)) {
                    if (is_a($currentNode->direction, East::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new East(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x - 1, $currentNode->y, $nextFromDirection)];
                }
            }
            if ($currentNode->y > 0 && !is_a($currentNode->direction, North::class)) {
                if ($currentNode->direction != new South(3)) {
                    if (is_a($currentNode->direction, South::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new South(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x, $currentNode->y - 1, $nextFromDirection)];
                }
            }
            if ($currentNode->x < $width - 1 && !is_a($currentNode->direction, East::class)) {
                if ($currentNode->direction != new West(3)) {
                    if (is_a($currentNode->direction, West::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new West(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x + 1, $currentNode->y, $nextFromDirection)];
                }
            }
            if ($currentNode->y < $height - 1 && !is_a($currentNode->direction, South::class)) {
                if ($currentNode->direction != new North(3)) {
                    if (is_a($currentNode->direction, North::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new North(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x, $currentNode->y + 1, $nextFromDirection)];
                }
            }

            foreach ($neighbours as $neighbourSet) {
                list($from, $neighbour) = $neighbourSet;
                if (isset($nodes[$neighbour])) {
                    if ($currentNode->distance + $nodes[$neighbour]->cost < $nodes[$neighbour]->distance) {
                        $nodes[$neighbour]->from = $currentNode->key();
                        $nodes[$neighbour]->distance = $currentNode->distance + $nodes[$neighbour]->cost;
                        $nodeDistances[$nodes[$neighbour]->key()] = $nodes[$neighbour]->distance;
                    }
                }
            }
            asort($nodeDistances);
            $nextNode = array_key_first($nodeDistances);
            $currentNode = $nodes[$nextNode];
            unset($nodes[$nextNode]);
            unset($nodeDistances[$nextNode]);

            // new current node
        } while ($currentNode->x != $width - 1 || $currentNode->y != $height - 1);
        $distance = $currentNode->distance;

        // Part 2
        /** @var Node[] $nodes */
        list($nodes, $width, $height) = static::parseData(explode(PHP_EOL, $inputStream));
        $currentNode = array_shift($nodes);
        $nodeDistances = [];
        do {
            $neighbours = [];
            if ($currentNode->x > 0 && !is_a($currentNode->direction, West::class)) {
                if ($currentNode->direction != new East(3)) {
                    if (is_a($currentNode->direction, East::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new East(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x - 1, $currentNode->y, $nextFromDirection)];
                }
            }
            if ($currentNode->y > 0 && !is_a($currentNode->direction, North::class)) {
                if ($currentNode->direction != new South(3)) {
                    if (is_a($currentNode->direction, South::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new South(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x, $currentNode->y - 1, $nextFromDirection)];
                }
            }
            if ($currentNode->x < $width - 1 && !is_a($currentNode->direction, East::class)) {
                if ($currentNode->direction != new West(3)) {
                    if (is_a($currentNode->direction, West::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new West(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x + 1, $currentNode->y, $nextFromDirection)];
                }
            }
            if ($currentNode->y < $height - 1 && !is_a($currentNode->direction, South::class)) {
                if ($currentNode->direction != new North(3)) {
                    if (is_a($currentNode->direction, North::class)) {
                        $nextFromDirection = $currentNode->direction->next();
                    } else {
                        $nextFromDirection = new North(1);
                    }
                    $neighbours[] = [$nextFromDirection, sprintf("%s-%s-%s", $currentNode->x, $currentNode->y + 1, $nextFromDirection)];
                }
            }

            foreach ($neighbours as $neighbourSet) {
                list($from, $neighbour) = $neighbourSet;
                if (isset($nodes[$neighbour])) {
                    if ($currentNode->distance + $nodes[$neighbour]->cost < $nodes[$neighbour]->distance) {
                        $nodes[$neighbour]->from = $currentNode->key();
                        $nodes[$neighbour]->distance = $currentNode->distance + $nodes[$neighbour]->cost;
                        $nodeDistances[$nodes[$neighbour]->key()] = $nodes[$neighbour]->distance;
                    }
                }
            }
            asort($nodeDistances);
            $nextNode = array_key_first($nodeDistances);
            $currentNode = $nodes[$nextNode];
            unset($nodes[$nextNode]);
            unset($nodeDistances[$nextNode]);

            // new current node
        } while ($currentNode->x != $width - 1 || $currentNode->y != $height - 1);
        $distance2 = $currentNode->distance;


        return new SolutionResult(
            17,
            new UnitResult('', $distance, ''),
            new UnitResult('', $distance2, '')
        );
    }

    /**
     * @param mixed $width
     * @param mixed $height
     * @return string
     */
    private static function getCoordIndex(mixed $width, mixed $height): string
    {
        return sprintf("%d-%d", $width - 1, $height - 1);
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
