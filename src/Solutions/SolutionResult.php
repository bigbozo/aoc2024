<?php

namespace Bizbozo\AdventOfCode\Solutions;


use Bramus\Ansi\Ansi;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Ansi\Writers\StreamWriter;

class SolutionResult
{

    public UnitResult $solution_part1;
    public UnitResult $solution_part2;
    private int $day;

    public function __construct($day, $solution_part1, $solution_part2)
    {

        $this->solution_part1 = $solution_part1;
        $this->solution_part2 = $solution_part2;
        $this->day = $day;
    }

    public function output($headline)
    {
        $ansi = new Ansi(new StreamWriter("php://stdout"));
        $ansi->color(SGR::COLOR_FG_YELLOW)
            ->text("DAY " . $this->day . ' ' . $headline)
            ->lf()
            ->nostyle()
            ->text("Part 1: ")
            ->color(SGR::COLOR_FG_GREEN)
            ->text($this->solution_part1->output())
            ->nostyle()
            ->lf()
            ->text("Part 2: ")
            ->color(SGR::COLOR_FG_GREEN)
            ->text($this->solution_part2->output())
            ->nostyle()
            ->lf();
    }

}
