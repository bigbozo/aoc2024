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
        return "Print Queue";
    }

    /**
     * @param mixed $pageNumbers
     * @param array $pagesInvalidBehind
     * @return array
     */
    private function sortPages(mixed $pageNumbers, array $pagesInvalidBehind): array
    {
        $invalidPages = [];
        foreach ($pageNumbers as $id => $pageNumber) {
            if (isset($invalidPages[$pageNumber])) {
                $pageNumbers = array_merge(
                    array_slice($pageNumbers, 0, $id - 1),
                    [$pageNumber],
                    [$pageNumbers[$id-1]],
                    array_slice($pageNumbers, $id + 1)
                );
                return $this->sortPages($pageNumbers, $pagesInvalidBehind);
            }
            if (isset($pagesInvalidBehind[$pageNumber])) {
                foreach ($pagesInvalidBehind[$pageNumber] as $n) {
                    $invalidPages[$n] = true;
                }
            }
        }
        return $pageNumbers;
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        list($pairs, $pages) = explode(PHP_EOL . PHP_EOL, $inputStream);

        $pagesInvalidBehind = [];
        foreach (explode(PHP_EOL, $pairs) as $pair) {
            $pair = explode("|", $pair);
            $pagesInvalidBehind[$pair[1]][] = $pair[0];
        }

        $pages = array_map(fn($page) => explode(',', $page), array_filter(explode(PHP_EOL, $pages), 'trim'));

        $sum = 0;
        $unsolved = [];
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
            } else {
                $unsolved[] = $pageNumbers;
            }
        }

        $sum2 = 0;
        foreach ($unsolved as $pageNumbers) {
            $pageNumbers = $this->sortPages($pageNumbers, $pagesInvalidBehind);
            $sum2 += $pageNumbers[floor(count($pageNumbers) / 2)];
        }


        return new SolutionResult(
            5,
            new UnitResult("%s is the middle-page sum of all valid updates", [$sum]),
            new UnitResult('The middle-page-sum of all patched updates is %s', [$sum2])
        );
    }

}
