<?php

namespace Bizbozo\AdventOfCode\Year2024\Day09;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Solutions\SolutionResult;
use Bizbozo\AdventOfCode\Solutions\UnitResult;
use Bizbozo\AdventOfCode\Utility\Parser;
use Override;

class Solution implements SolutionInterface
{

    private function parseData(string $stream)
    {
        return 1;
    }

    public function getTitle(): string
    {
        return "Disk Fragmenter";
    }

    #[Override] public function solve(string $inputStream, ?string $inputStream2 = null): SolutionResult
    {

        $data = Parser::numChars($inputStream);
        // no space at the end
        if (!count($data) % 2) array_pop($data);

        $spaces = array_column(array_chunk($data, 2), 1);
        $files = array_column(array_chunk($data, 2), 0);

        $widthPacked = array_sum($files);
        $disk = array_fill(0, $widthPacked, 'x');
        $cursor = 0;
        $dir = 1;
        for ($i = 0; $i < count($data); $i += 2) {
            $len = $data[$i];
            while ($len) {
                if ($disk[$cursor] == 'x') {
                    $disk[$cursor] = $i / 2;
                    $len--;
                    if (!$len && $dir == 1) {
                        $cursor += $data[$i + 1];
                    }
                }
                $cursor += $dir;
                if ($cursor >= $widthPacked) {
                    $dir = -1;
                    $cursor = $widthPacked - 1;
                }
            }
        }
        array_walk($disk, function (&$i, $k) {
            $i = $i * $k;
        });
        $sum = array_sum($disk);

        array_walk($data, function (&$i, $k) {
            $i = ['type' => $k % 2 ? 's' : 'f', 'id' => $k >> 1, 'len' => $i, 'pos' => $k];
        });


        $spaces = array_values(array_filter($data, function ($item) {
            return $item['type'] == 's';
        }));
        $files = array_values(array_filter($data, function ($item) {
            return $item['type'] == 'f';
        }));

        $disk2[] = [$files[0]];
        $cursorR = count($files) - 1;
        do {
            $file = &$files[$cursorR];
            foreach ($spaces as &$space) {
                if ($space['id'] > $cursorR) {
                    break;
                }
                if ($space['len'] >= $file['len']) {
                    $space['files'][] = $file;
                    $space['len'] -= $file['len'];
                    $injected = true;
                    $file['id'] = 0;
                    break;
                }
            }
            $cursorR--;
        } while ($cursorR);

        $disk2 = array_merge($spaces, $files);
        usort($disk2, fn($a, $b) => $a['pos'] <=> $b['pos']);
        $finalDisk = [];
        foreach ($disk2 as $item) {
            if (count($item['files'] ?? [])) {
                $finalDisk = array_merge($finalDisk, $item['files']);
            }
            $finalDisk[] = $item;
        }

        $sum2 = 0;
        $start = 0;
        foreach ($finalDisk as $item) {
            if ($item['type'] == 'f') {
                $sum2 += $item['id'] * ($this->arithmeticSum($start + $item['len'] - 1) - $this->arithmeticSum($start - 1));
            }
            $start += $item['len'];
        }

        return new SolutionResult(
            9,
            new UnitResult("The disks filesystem checksum is %s ", [$sum]),
            new UnitResult('The checksum when not fragmenting files is %s', [$sum2])
        );
    }

    private function arithmeticSum($n)
    {
        return $n * ($n + 1) / 2;
    }
}
