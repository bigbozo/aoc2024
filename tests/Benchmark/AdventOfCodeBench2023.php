<?php

namespace Bizbozo\AdventOfCode\Tests\Benchmark;

use Bizbozo\AdventOfCode\Traits\UsesInput;

class AdventOfCodeBench2023
{
    use UsesInput;







    /**
     * @Revs(1000)
     */
    public function benchDay01()
    {
        (new \Bizbozo\AdventOfCode\Year2023\Day01\Solution)
            ->solve(file_get_contents($this->getInputFilename(2023, 1)));
    }


    /**
     * @Revs(1000)
     */
    public function benchDay20(): void
    {
//        (new \Bizbozo\AdventOfCode\Year2023\Day20\Solution)
//            ->solve(file_get_contents($this->getInputFilename(2023, 20)));
    }

}
