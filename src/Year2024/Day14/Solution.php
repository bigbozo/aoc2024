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
        if (!is_dir('out')) mkdir('out');
        imagepng($img, "docs/day14.$steps.png");
    }

    /**
     * @param int $steps
     * @param int $height
     * @param int $width
     * @param array $robots
     * @return array|\int[][]
     */
    public function moveRobots(int $steps, int $height, int $width, array $robots): array
    {
        return array_map(function ($robot) use ($steps, $height, $width) {
            [$x, $y] = Parser::numbers($robot['p'], ',');
            [$vx, $vy] = Parser::numbers($robot['v'], ',');

            $target = [
                ($x + $steps * $vx) % $width,
                ($y + $steps * $vy) % $height,
            ];
            while ($target[0] < 0) $target[0] += $width;
            while ($target[1] < 0) $target[1] += $height;
            return $target;

        }, $robots);
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


        // differentiate between test and live data
        if (strlen($inputStream) < 300) {
            $width = 11;
            $height = 7;
        } else {
            $width = 101;
            $height = 103;
        }
        $hCenter = intdiv($width, 2);
        $vCenter = intdiv($height, 2);


        $maxTicks = $this->getMaxTicks($baseRobots, $width, $height);
        for ($steps = 0; $steps < $maxTicks; $steps++) {
            $robots = $this->moveRobots($steps, $height, $width, $baseRobots);
            if ($this->checkTree($robots, $width)) {
                $this->paint($width, $height, $robots, $steps);
                break;
            };
            // process images
        }


        $robots = $this->moveRobots(100, $height, $width, $baseRobots);
        $quadrantScan = ArrayUtility::partition(
            $robots,
            fn($r) => ($r[0] == $hCenter || $r[1] == $vCenter)
                // doesn't count
                ? 0
                : ($r[0] > $hCenter ? 2 : 0) + ($r[1] > $vCenter ? 1 : 0) + 1
        );

        unset($quadrantScan[0]);
        $product = array_product(array_map(fn($q) => count($q), $quadrantScan));

        return new SolutionResult(
            14,
            new UnitResult("The 1st answer is %s", [$product]),
            new UnitResult('the christmas tree appears after %s seconds', [$steps])
        );
    }

    private function checkTree(array $robots, $width): bool
    {
        $robotsAtField = ArrayUtility::partition(
            $robots,
            fn($r) => $width * $r[1] + $r[0]
        );
        return count($robots) == count($robotsAtField);
    }

    private function getMaxTicks($robots, $width, int $height): int
    {
        $period = 1;
        foreach ($robots as $robot) {

            [$vx, $vy] = Parser::numbers($robot['v'], ',');

            $period = gmp_lcm($period,
                gmp_lcm(
                    $width / gmp_gcd($vx, $width),
                    $height / gmp_gcd($vy, $height)
                )
            );
        }
        return (int)$period;
    }
}
