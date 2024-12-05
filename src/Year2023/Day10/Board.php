<?php

namespace Bizbozo\AdventOfCode\Year2023\Day10;

use Exception;
use OutOfBoundsException;

class Board
{
    protected int $width;
    protected int $height;
    protected string $data;
    protected array $metaData;

    private function __construct($width, $height, $data)
    {

        $this->width = $width;
        $this->height = $height;
        $this->data = $data;
        $this->metaData = [];
    }

    public static function build($lines): Board
    {
        $lines = array_filter(explode(PHP_EOL, $lines), fn($line) => trim($line));
        $width = strlen($lines[0]);
        $height = count($lines);
        return new self($width, $height, implode('', $lines));
    }

    public function find(string $s): array
    {
        $positions = [];
        $position = -1;
        while (($position = strpos($this->data, $s, $position + 1)) !== false) {
            $positions[] = $this->getCoords($position);
        }

        return $positions;
    }

    /**
     * Returns the coordinates based on the given index
     *
     * @param int $index The index value
     * @return array The coordinates in the form [x, y]
     */
    private function getCoords(int $index): array
    {
        return [$index % $this->width, (int)($index / $this->width)];
    }

    public function explore($x, $y, $val = 0): int
    {
        $this->setMetaData($x, $y, $val);

        if ($val == 0) {
            // Start
            $top = $this->checkAndExplore($x, $y - 1, ['|', 'F', '7']);
            $bottom = $this->checkAndExplore($x, $y + 1, ['|', 'L', 'J']);
            $right = $this->checkAndExplore($x + 1, $y, ['-', 'J', '7']);
            $left = $this->checkAndExplore($x - 1, $y, ['-', 'L', 'F']);

            if ($top && $left) $this->set($x, $y, 'J');
            if ($top && $right) $this->set($x, $y, 'L');
            if ($bottom && $left) $this->set($x, $y, '7');
            if ($bottom && $right) $this->set($x, $y, 'F');
            if ($bottom && $top) $this->set($x, $y, '|');
            if ($left && $right) $this->set($x, $y, '-');

            // after exploring every reachable pipe from start
            // should have its metadata set to the distance from start, so the max-distance has to be in metadata
            return max($this->metaData);
        }
        $neighbours = $this->getPipeNeighbours($x, $y);
        foreach ($neighbours as $neighbour) {
            $neighbourValue = $this->getMetaData($neighbour[0], $neighbour[1]);
            if ($neighbourValue === null) {
                // unerforscht
                $this->explore($neighbour[0], $neighbour[1], $val + 1);
            } else {
                if ($neighbourValue > $val + 1) {
                    // kürzeren Weg zu Neighbour gefunden
                    $this->explore($neighbour[0], $neighbour[1], $val + 1);
                }
            }
        }
        return 0;
    }

    private function checkAndExplore($x, $y, array $items): int
    {
        try {
            if (in_array($this->get($x, $y), $items)) {
                $this->explore($x, $y, 1);
                return 1;
            }
        } catch (OutOfBoundsException) {
        }

        return 0;
    }

    public function get(int $x, int $y): string
    {
        return substr($this->data, $this->getIndex($x, $y), 1);
    }

    /**
     * Gets the index where the pipe for the given coordinates is saved in the datastream
     *
     * @param int $x The x-coordinate
     * @param int $y The y-coordinate
     * @return int The calculated index
     * @throws OutOfBoundsException When either x or y is out of bounds
     */
    private function getIndex(int $x, int $y): int
    {
        if ($x < 0) throw new OutOfBoundsException('x out of bounds');
        if ($y < 0) throw new OutOfBoundsException('y out of bounds');
        if ($x >= $this->width) throw new OutOfBoundsException('x out of bounds');
        if ($y >= $this->height) throw new OutOfBoundsException('y out of bounds');
        return $x + $this->width * $y;
    }

    public function set(int $x, int $y, string $pipe): void
    {
        $this->data = substr_replace($this->data, $pipe, $this->getIndex($x, $y), 1);
    }

    /**
     * returns the neighbours for pipe
     * @param $x
     * @param $y
     * @return array
     */
    private function getPipeNeighbours($x, $y): array
    {
        return match ($this->get($x, $y)) {
            '7' => [[$x - 1, $y], [$x, $y + 1]],
            '|' => [[$x, $y - 1], [$x, $y + 1]],
            '-' => [[$x - 1, $y], [$x + 1, $y]],
            'L' => [[$x + 1, $y], [$x, $y - 1]],
            'J' => [[$x - 1, $y], [$x, $y - 1]],
            'F' => [[$x + 1, $y], [$x, $y + 1]],
            default => throw new OutOfBoundsException(),
        };
    }

    /**
     * Returns the metadata associated with the given coordinates.
     *
     * @param int $x The x-coordinate.
     * @param int $y The y-coordinate.
     * @return string|null The metadata associated with the coordinates, or null if no metadata exists.
     */
    public function getMetaData(int $x, int $y): ?string
    {
        return $this->metaData[$this->getIndex($x, $y)] ?? null;
    }

    /**
     * Sets the metadata for a given coordinate in the grid.
     *
     * @param int $x The x-coordinate of the grid.
     * @param int $y The y-coordinate of the grid.
     * @param int $meta The metadata to be set.
     * @return void
     */
    public function setMetaData(int $x, int $y, int $meta): void
    {
        $this->metaData[$this->getIndex($x, $y)] = $meta;
    }

    /**
     * calculates the area surrounded by the pipe system
     * @return int the calculated area
     * @throws OutOfBoundsException|Exception if an invalid pipe character is encountered
     */
    public function calculateArea(): int
    {
        $area = 0;
        for ($y = 0; $y < $this->height; $y++) {
            $bottom = false;
            $top = false;
            $counting = 0;
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->getMetaData($x, $y) === null) {
                    // does not belong to loop
                    $area += $counting;
                    if ($counting) $this->set($x, $y, 'O');
                    continue;
                }
                // swapping between inside and outside state occurs when the pipe crosses
                // from top to bottom or vice versa
                switch ($this->get($x, $y)) {
                    case 'F':
                        // starts context from bottom
                        $bottom = true;
                        break;
                    case 'L':
                        // starts context from top
                        $top = true;
                        break;
                    case '|':
                        // crossing switches inside/outside
                        $counting = 1 - $counting;
                        break;
                    case '7':
                        if ($top) {
                            // pipe crossed row
                            $counting = 1 - $counting;
                            $top = false;
                        }
                        // reset bottom context
                        $bottom = false;
                        break;
                    case 'J':
                        if ($bottom) {
                            // pipe crossed row
                            $counting = 1 - $counting;
                            $bottom = false;
                        }
                        // reset top context
                        $top = false;
                        break;
                    case '-':
                        // no influence on context
                        break;
                    default:
                        throw new OutOfBoundsException('this should never happen');
                }
            }
        }

        // draw the image for debugging purposes
        //$this->drawImage($area);

        return $area;

    }

    /**
     * Draws an image highlighting the pipe system in magenta and the surrounded area in yellow
     *
     * @param mixed $area The area to draw the image for.
     * @return void
     * @throws Exception if an error occurred while creating the image.
     *
     */
    private function drawImage(mixed $area): void
    {
        $image = imagecreate($this->width, $this->height);
        $plot_color = imagecolorallocate($image, 233, 14, 91);
        $inside_color = imagecolorallocate($image, 212, 212, 33);
        foreach ($this->metaData as $index => $value) {
            $coords = $this->getCoords($index);
            imagerectangle($image, $coords[0], $coords[1], $coords[0], $coords[1], $plot_color);
        }
        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                if ($this->get($x, $y) == 'O') {
                    imagerectangle($image, $x, $y, $x, $y, $inside_color);
                }
            }
        }
        imagepng($image, '../output/day10-' . $area . '.png');
    }


}
