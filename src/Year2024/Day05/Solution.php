<?php

namespace Bizbozo\AdventOfCode\Year2024\Day05;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Override;

class Solution implements SolutionInterface
{

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Day 5 - ";
    }

    #[Override] public function solve(string $inputStream, string $inputStream2 = null): SolutionResult
    {

        list($pairs, $pages) = explode(PHP_EOL . PHP_EOL, $inputStream);

        $pagesInvalidBehind = [];
        foreach (explode(PHP_EOL, $pairs) as $pair) {
            $pair = explode("|", $pair);
            $pagesInvalidBehind[$pair[1]][] = $pair[0];
        }

        $pages = array_map(fn($page) => explode(',', $page), array_filter(explode(PHP_EOL, $pages), 'trim'));

        $sum = 0;
        foreach ($pages as $pageNumbers) {

            $invalidPages = [];
            $pageOrderValid = true;
            foreach ($pageNumbers as $pageNumber) {
                if (isset($invalidPages[$pageNumber])) {
                    $pageOrderValid = false;
                    break;
                }
                if (isset($pagesInvalidBehind[$pageNumber])) {
                    foreach ($pagesInvalidBehind[$pageNumber] as $n) {
                        $invalidPages[$n] = true;
                    }
                }
            }
            if ($pageOrderValid) {
                $sum += $pageNumbers[floor(count($pageNumbers) / 2)];
            }
        }
        $sum = 0;
        foreach ($pages as $pageNumbers) {

            $invalidPages = [];
            $pageOrderValid = true;
            foreach ($pageNumbers as $pageNumber) {
                if (isset($invalidPages[$pageNumber])) {
                    $pageOrderValid = false;
                    break;
                }
                if (isset($pagesInvalidBehind[$pageNumber])) {
                    foreach ($pagesInvalidBehind[$pageNumber] as $n) {
                        $invalidPages[$n] = true;
                    }
                }
            }
            if ($pageOrderValid) {
                $sum += $pageNumbers[floor(count($pageNumbers) / 2)];
            }
        }


        return new SolutionResult(
            5,
            new UnitResult("The 1st answer is %s", [$sum]),
            new UnitResult('The 2nd answer is %s', [0])
        );
    }
}
