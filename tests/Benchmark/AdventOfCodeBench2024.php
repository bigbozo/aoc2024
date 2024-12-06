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

}
