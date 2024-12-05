<?php

namespace Bizbozo\AdventOfCode\Year2023\Day18;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;
use PhpBench\Environment\Provider\Php;

enum Direction
{
    case UP;
    case RIGHT;
    case DOWN;
    case LEFT;

    /**
     * @param string $char
     * @return Direction
     */
    public static function from(string $char)
    {
        return match ($char) {
            'U' => Direction::UP,
            'D' => Direction::DOWN,
            'L' => Direction::LEFT,
            'R' => Direction::RIGHT,
        };
    }

    public function char(): string
    {
        return match ($this) {
            static::UP => 'U',
            static::DOWN => 'D',
            static::LEFT => 'L',
            static::RIGHT => 'R',
        };
    }

    public function orientation(Direction $other): int
    {
        return match ($this) {
            self::UP => match ($other) {
                self::UP => 0,
                self::RIGHT => 1,
                self::DOWN => 0,
                self::LEFT => -1,
            },
            self::RIGHT => match ($other) {
                self::UP => -1,
                self::RIGHT => 0,
                self::DOWN => 1,
                self::LEFT => 0,
            },

            self::DOWN => match ($other) {
                self::UP => 0,
                self::RIGHT => -1,
                self::DOWN => 0,
                self::LEFT => 1,
            },

            self::LEFT => match ($other) {
                self::UP => 1,
                self::RIGHT => 0,
                self::DOWN => -1,
                self::LEFT => 0,
            }
        };
    }
}

class Board
{

    private $data = [];
    private int $tx;
    private int $ty;

    public function __construct(public int $width, public int $height)
    {
        $this->data = array_fill(0, $this->width * $this->height, '.');
    }

    public function set(int $x, int $y, string $char)
    {
        $x += $this->tx;
        $y += $this->ty;
        if ($this->isValid($x, $y)) {
            $this->data[$x + $this->width * $y] = $char;
        }
    }

    public function get(int $x, int $y)
    {
        $x += $this->tx;
        $y += $this->ty;
        if ($this->isValid($x, $y)) {
            return $this->data[$x + $this->width * $y];
        } else {
            return null;
        }
    }

    public function print()
    {
        echo implode(PHP_EOL, array_map(fn($item) => implode("", $item), array_chunk($this->data, $this->width))) . PHP_EOL . PHP_EOL;
    }

    public function count($char)
    {
        return count(array_filter($this->data, fn($item) => $item === $char));
    }


    public function floodFill($x, $y, $char, $fromChar = null)
    {

        if (!$this->isValid($x, $y)) return;

        if ($fromChar == null) {
            $fromChar = $this->get($x, $y);
        }

        $queue = [[$x, $y]];
        while (count($queue)) {
            list($x, $y) = array_pop($queue);
            $this->set($x, $y, $char);
            if ($this->get($x + 1, $y) == $fromChar) {
                $queue[] = [$x + 1, $y];
            }
            if ($this->get($x - 1, $y) == $fromChar) {
                $queue[] = [$x - 1, $y];
            }
            if ($this->get($x, $y + 1) == $fromChar) {
                $queue[] = [$x, $y + 1];
            }
            if ($this->get($x, $y - 1) == $fromChar) {
                $queue[] = [$x, $y - 1];
            }
        }
    }

    public function setTranslation(int $tx, int $ty)
    {
        $this->tx = $tx;
        $this->ty = $ty;

    }

    /**
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function isValid(int $x, int $y): bool
    {
        return $x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height;
    }
}

class Instruction
{

    public function __construct(
        public Direction $direction,
        public int       $amount,
        public string    $color,
        public Direction $direction2,
        public int       $amount2
    )
    {
    }
}

class Solution implements SolutionInterface
{

    /**
     * @param array $lines
     * @return Instruction[]
     */
    private static function parseData(array $lines): array
    {
        return array_map(function ($line) {
            list($dir, $amount, $color) = explode(" ", $line);

            $amount2 = hexdec(substr($color, 2, 5));
            $dir2 = match (substr($color, 7, 1)) {
                "0" => Direction::RIGHT,
                "1" => Direction::DOWN,
                "2" => Direction::LEFT,
                "3" => Direction::UP
            };

            return new Instruction(Direction::from($dir), (int)$amount, $color, $dir2, (int)$amount2);

        }, array_filter($lines, fn($line) => trim($line)));
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $instructions = static::parseData(explode(PHP_EOL, $inputStream));

        $minX = $maxX = $minY = $maxY = 0;
        $cursorX = 0;
        $cursorY = 0;
        foreach ($instructions as $instruction) {
            switch ($instruction->direction) {
                case Direction::UP:
                    $cursorY -= $instruction->amount;
                    break;
                case Direction::RIGHT:
                    $cursorX += $instruction->amount;
                    break;
                case Direction::DOWN:
                    $cursorY += $instruction->amount;
                    break;
                case Direction::LEFT:
                    $cursorX -= $instruction->amount;
                    break;
            }
            $minX = min($minX, $cursorX);
            $maxX = max($maxX, $cursorX);
            $minY = min($minY, $cursorY);
            $maxY = max($maxY, $cursorY);
        }
        $width = $maxX - $minX + 1;
        $height = $maxY - $minY + 1;


        $board = new Board($width + 2, $height + 2);
        $board->setTranslation(-$minX + 1, -$minY + 1);
        $cursorX = $cursorY = 0;
        foreach ($instructions as $instruction) {
            switch ($instruction->direction) {
                case Direction::UP:
                    for ($i = 0; $i < $instruction->amount; $i++) {
                        $board->set($cursorX, $cursorY, '#');
                        $cursorY--;
                    }
                    break;
                case Direction::RIGHT:
                    for ($i = 0; $i < $instruction->amount; $i++) {
                        $board->set($cursorX, $cursorY, '#');
                        $cursorX++;
                    }
                    break;
                case Direction::DOWN:
                    for ($i = 0; $i < $instruction->amount; $i++) {
                        $board->set($cursorX, $cursorY, '#');
                        $cursorY++;
                    }
                    break;
                case Direction::LEFT:
                    for ($i = 0; $i < $instruction->amount; $i++) {
                        $board->set($cursorX, $cursorY, '#');
                        $cursorX--;
                    }
                    break;
            }
        }
        $board->setTranslation(0, 0);
//        $board->print();
        $lineCount = $board->count('#');
        $board->floodFill(0, 0, '#');
//        $board->print();
        $fillCount = $board->count('#');

        $finalCount = ($board->width * $board->height) - ($fillCount - $lineCount);

        // Part 2
        $x = 0;
        $y = 0;
        $orientation = 0;
        $oldDirection = end($instructions)->direction;
        foreach ($instructions as $instruction) {
            $orientation += $oldDirection->orientation($instruction->direction);
            $oldDirection = $instruction->direction;
            $instruction->direction = $instruction->direction2;
            $instruction->amount = $instruction->amount2;
        }
        $loopTurnsRight = $orientation > 0;
        if (!$loopTurnsRight) die('Not right tuerning loop');

        $oldDirection = end($instructions)->direction;

        $offset = match ($oldDirection->char() . $instructions[0]->direction->char()) {
            'UR' => [0, 0],
            'UL' => [0, 1],
            'DR' => [1, 0],
            'DL' => [1, 1],
            'LU' => [0, 1],
            'LD' => [1, 1],
            'RU' => [0, 0],
            'RD' => [1, 0],
            default => 1
        };

        $points = [[bcadd($x, $offset[0]), bcadd($y, $offset[1])]];

        $area = 0;

        foreach ($instructions as $id => $instruction) {
            $amount = $instruction->amount;
            switch ($instruction->direction) {
                case Direction::UP:
                    $y -= $amount;
                    break;
                case Direction::RIGHT:
                    $x += $amount;
                    break;
                case Direction::DOWN:
                    $y += $amount;
                    break;
                case Direction::LEFT:
                    $x -= $amount;
                    break;
            }
            $cornertype = $instruction->direction->char() . $instructions[($id + 1) % count($instructions)]->direction->char();
            $offset = match ($cornertype) {
                'UR' => [0, 0],
                'UL' => [0, 1],
                'DR' => [1, 0],
                'DL' => [1, 1],
                'LU' => [0, 1],
                'LD' => [1, 1],
                'RU' => [0, 0],
                'RD' => [1, 0],
                default => 1
            };


            $points[] = [bcadd($x, $offset[0]), bcadd($y, $offset[1])];
            $oldDirection = $instruction->direction;
        }


        for ($i = 0; $i < count($points) - 1; $i++) {
            $area = bcadd($area, bcadd(bcmul($points[$i][0], $points[$i + 1][1]), -bcmul($points[$i][1], $points[$i + 1][0])));
            //       A += points[i] . x * points[(i + 1) % points . length] . y - points[i] . y * points[(i + 1) % points . length] . x
        }
        // 133125706798409 is too low
        // 133125706798383
        // 133125706798385
        $area = bcdiv($area, 2);


        /*        echo 952408144115 - $area;
                exit;*/


        return new SolutionResult(
            18,
            new UnitResult('', $finalCount, ''),
            new UnitResult('', $area, '')
        );
    }

    public function getTitle(): string
    {
        return 'tbd.';
    }
}
