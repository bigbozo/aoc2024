<?php

namespace Bizbozo\AdventOfCode\Year2024\Day14;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\ArrayUtility;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;

class Solution implements SolutionInterface
{

    /**
     * @param int $width
     * @param int $height
     * @param array $robots
     * @param int $steps
     * @return void
     */
    public function paint(int $width, int $height, array $robots, int $steps): void
    {
        $img = imagecreate($width, $height);
        $bg = imagecolorallocate($img, 0, 0, 0);
        $green = imagecolorallocate($img, 0, 255, 0);
        foreach ($robots as $robot) {
            imagesetpixel($img, $robot[0], $robot[1], $green);
        }
        imagepng($img, "out/day14.$steps.png");
    }

    /**
     * @param int $steps
     * @param int $height
     * @param int $width
     * @param array $baseRobots
     * @return array|\int[][]
     */
    public function moveRobots(int $steps, int $height, int $width, array $baseRobots): array
    {
        $robots = array_map(function ($robot) use ($steps, $height, $width) {
            [$x, $y] = Parser::numbers($robot['p'], ',');
            [$vx, $vy] = Parser::numbers($robot['v'], ',');

            $target = [
                ($x + $steps * $vx) % $width,
                ($y + $steps * $vy) % $height,
            ];
            while ($target[0] < 0) $target[0] += $width;
            while ($target[1] < 0) $target[1] += $height;
            return $target;

        }, $baseRobots);
        return $robots;
    }

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Restroom Redoubt";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {
        $baseRobots = array_map(
            fn($line) => Parser::values($line),
            Parser::lines($inputStream)
        );

        if (strlen($inputStream) < 300) {
            $width = 11;
            $height = 7;
        } else {
            $width = 101;
            $height = 103;
        }
        $horizontal_center = intdiv($width, 2);

        $steps = 0;


        for ($steps = 0; $steps < 10000; $steps++) {
            $robots = $this->moveRobots($steps, $height, $width, $baseRobots);
            // process images
            $this->paint($width, $height, $robots, $steps);
        }


        $robots = $this->moveRobots(100, $height, $width, $baseRobots);
        $quadrantScan = ArrayUtility::partition(
            $robots,
            function ($robot) use ($width, $height) {
                $horizontal_center = intdiv($width, 2);
                $vertical_center = intdiv($height, 2);
                if ($robot[0] == $horizontal_center || $robot[1] == $vertical_center) {
                    return 0;
                }
                $quadrant = ($robot[0] > $horizontal_center ? 2 : 0)
                    + ($robot[1] > $vertical_center ? 1 : 0) + 1;
                return $quadrant;
            });

        unset($quadrantScan[0]);
        $product = 1;
        foreach ($quadrantScan as $item) {
            $product *= count($item);
        }

        return new SolutionResult(
            14,
            new UnitResult("The 1st answer is %s", [$product]),
            new UnitResult('inspect the images', [$steps])
        );
    }
}
