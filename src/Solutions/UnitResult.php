<?php

namespace Bizbozo\AdventOfCode\Solutions;

use Bramus\Ansi\Ansi;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;

class UnitResult
{

    private string $label;
    private array $params;

    public function __construct(string $label, array $params)
    {
        $this->label = $label;
        $this->params = $params;
    }

    public function output()
    {
        return sprintf($this->label, ...$this->params);
    }

}
