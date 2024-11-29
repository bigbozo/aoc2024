<?php

namespace Bizbozo\AdventOfCode\Traits;

trait UsesInput
{
    public function getInputFilename(int $day)
    {
        return sprintf("%s/../../input/day%s.txt", __DIR__, $this->leadingZero($day));
    }

    public function getTestInputFilenames(int $day)
    {
        return [
            sprintf("%s/../../input/day%s-test.txt", __DIR__, $this->leadingZero($day)),
            sprintf("%s/../../input/day%s-test2.txt", __DIR__, $this->leadingZero($day))
        ];
    }

    /**
     * @param int $day
     * @return string
     */
    public function leadingZero(int $day): string
    {
        return str_pad($day, 2, '0', STR_PAD_LEFT);
    }
}
