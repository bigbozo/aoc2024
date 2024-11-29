<?php

namespace Bizbozo\AdventOfCode\Solutions;

interface SolutionInterface
{
    public static function solve(string $inputStream, string $inputStream2 = null): SolutionResult;
}
