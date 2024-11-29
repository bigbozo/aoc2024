<?php

namespace Bizbozo\AdventOfCode\Solutions;

interface SolutionInterface
{
    public function solve(string $inputStream, string $inputStream2 = null): SolutionResult;
    public function getTitle(): string;
}
