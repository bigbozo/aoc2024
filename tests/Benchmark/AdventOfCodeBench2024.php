<?php

namespace Bizbozo\AdventOfCode\Tests\Benchmark;

use Bizbozo\AdventOfCode\Traits\UsesInput;

class AdventOfCodeBench2024
{
    use UsesInput;



    /**
     * @Revs(1000)
     */
    public function benchDay01(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day01\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 1)));
    }


    /**
     * @Revs(200)
     */
    public function benchDay02(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day02\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024,2)));
    }
    /**
     * @Revs(1000)
     */
    public function benchDay03(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day03\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024,3)));
    }

    /**
     * @Revs(20)
     */
    public function benchDay04(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day04\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 4)));
    }


    /**
     * @Revs(20)
     */
    public function benchDay05(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day05\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 5)));
    }


    /**
     * @Revs(2)
     */
    public function benchDay06(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day06\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 6)));
    }


    /**
     * @Revs(1)
     */
    public function benchDay07(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day07\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 7)));
    }


    /**
     * @Revs(1000)
     */
    public function benchDay08(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day08\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 8)));
    }


    /**
     * @Revs(5)
     */
    public function benchDay09(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day09\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 9)));
    }


    /**
     * @Revs(20)
     */
    public function benchDay10(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day10\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 10)));
    }


    /**
     * @Revs(20)
     */
    public function benchDay11(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day11\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 11)));
    }


    /**
     * @Revs(5)
     */
    public function benchDay12(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day12\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 12)));
    }


    /**
     * @Revs(1000)
     */
    public function benchDay13(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day13\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 13)));
    }


    /**
     * @Revs(1000)
     */
    public function benchDay14(): void
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day14\Solution)
            ->solve(file_get_contents($this->getInputFilename(2024, 14)));
    }

}
