<?php

namespace Bizbozo\AdventOfCode\Year2023\Day20;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;
use function PHPUnit\Framework\isEmpty;

class Solution implements SolutionInterface
{

    /**
     * @param string $inputStream
     * @return array
     */
    public function parseData(string $inputStream): array
    {
        $parts = array_map(
            function ($line) {
                $parts = explode(' -> ', $line);
                $type = substr($parts[0], 0, 1);
                $key = $type == 'b' ? $parts[0] : substr($parts[0], 1);
                $value = [
                    'type' => $type,
                    'targets' => preg_split('/,\s*/', $parts[1])
                ];
                switch ($type) {
                    case '%':
                        $value['state'] = false;
                        break;
                    case '&':
                        $value['inputs'] = [];
                        break;
                }
                return [
                    'key' => $key,
                    'value' => $value
                ];
            },
            Parser::lines($inputStream)
        );
        $parts = array_combine(
            array_column($parts, 'key'),
            array_column($parts, 'value')
        );

        foreach ($parts as $key => $part) {
            foreach ($part['targets'] as $target) {
                if ($parts[$target] ?? null && $parts[$target]['type'] == '&') {
                    $parts[$target]['inputs'][$key] = false;
                }
            }
        }
        return $parts;
    }


    public function getTitle(): string
    {
        return "Day 20 - ";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $parts = $this->parseData($inputStream);

        $hasRX=strpos($inputStream, 'rx')!==false;


        $pulseCounter = [false => 0, true => 0];
        $minSteps=false;
        $i=0;
        do {
            $queue = array_map(
                fn($target) => ['target' => $target, 'signal' => false, 'from' => 'broadcaster'],
                $parts['broadcaster']['targets']
            );
            $pulseCounter[false]+=($i<1000);
            while (count($queue)) {
                $step = array_shift($queue);

                $pulseCounter[$step['signal']]+=($i<1000);
                $targetId = $step['target'];
                if ($targetId=='rx' && !$step['signal'] && $minSteps===false){
                    $minSteps=$i;
                }
                if (!isset($parts[$targetId])) continue;
                $target =& $parts[$targetId];
                switch ($target['type']) {
                    case '%':
                        if ($step['signal'] === false) {
                            $target['state'] = !$target['state'];
                            foreach ($target['targets'] as $newTarget) {
                                $queue[] = ['target' => $newTarget, 'signal' => $target['state'], 'from' => $targetId];
                            }
                        }
                        break;
                    case '&':
                        $target['inputs'][$step['from']] = $step['signal'];
                        $signal = !empty(array_filter($target['inputs'], fn($t) => !$t));
                        foreach ($target['targets'] as $newTarget) {
                            $queue[] = ['target' => $newTarget, 'signal' => $signal, 'from' => $targetId];
                        }
                        break;
                }
            }
            $i++;
            echo !($i%10000) ? '.':'';
            echo !($i%1000000) ? PHP_EOL:'';
        } while ($i<1000 || ($hasRX && $minSteps===false));



        return new SolutionResult(
            20,
            new UnitResult("The 1st answer is %s", [$pulseCounter[false] * $pulseCounter[true]]),
            new UnitResult('The 2nd answer is %s', [$minSteps])
        );
    }
}
