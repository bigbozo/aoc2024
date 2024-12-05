<?php

namespace Bizbozo\AdventOfCode\Year2024\Day01;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;

class Solution implements SolutionInterface
{

  public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
  {

    // prepare data
    $lists=[[],[]];
    foreach(explode(PHP_EOL,$inputStream) as $line){
      if (trim($line)) {
        list($lists[0][],$lists[1][])=preg_split('/\s+/',trim($line));
      }
    }    
    // sort the lists
    sort($lists[0]);
    sort($lists[1]);

    // solve for part 1
    $sum=0;
    foreach($lists[0] as $id=>$val) {
      $sum+=abs($val-$lists[1][$id]);
    }

    // solve for part 2
    $sum2=0;
    $counts = array_count_values($lists[1]);
    foreach($lists[0] as $id=>$val) {
      $sum2+=$val * ($counts[$val] ?? 0);
    }

    return new SolutionResult(
      1,
      new UnitResult("The total distance between the lists amount to %s", [$sum]),
      new UnitResult('The similarity score between the lisfs is %s',[$sum2])
    );
  }

  public function getTitle(): string
  {
    return 'Historian Hysteria';
  }
}
