<?php

namespace Bizbozo\AdventOfCode\Tests\Benchmark;

use Bizbozo\AdventOfCode\Traits\UsesInput;

class AdventOfCodeBench
{
    use UsesInput;


    /**
     * @Revs(1000)
     */
    public function benchDay01()
    {
    (new \Bizbozo\AdventOfCode\Year2024\Day01\Solution)
      ->solve(file_get_contents($this->getInputFilename(1)));
    }


    /**
     * @Revs(200)
     */
    public function benchDay02()
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day02\Solution)->solve(file_get_contents($this->getInputFilename(2)));
    }
    /**
     * @Revs(1000)
     */
    public function benchDay03()
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day03\Solution)->solve(file_get_contents($this->getInputFilename(3)));
    }
    /**
     * @Revs(20)
     */
    public function benchDay04()
    {
        (new \Bizbozo\AdventOfCode\Year2024\Day04\Solution)->solve(file_get_contents($this->getInputFilename(4)));
    }






}
