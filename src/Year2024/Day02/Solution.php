<?php

namespace Bizbozo\AdventOfCode\Year2024\Day02;

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
    return "Red-Nosed Reports";
  }

  #[Override] public function solve(string $inputStream, string $inputStream2 = null): SolutionResult
  {

    $save=0;
    $lines = explode(PHP_EOL, $inputStream);
    foreach ($lines as $l){
      $n=explode(' ',$l);
      $s=array_shift($n);
      $valid=true;
      $dir=null;
      foreach($n as $num) {
        if ($num!=$s) $dir=$dir??$num-$s;


      } 
    }

    return new SolutionResult(
      2,
      new UnitResult("%s reports are safe", [0]),
      new UnitResult('The 2nd answer is %s',[0])
    );
  }
}
