<?php

namespace Bizbozo\AdventOfCode\Commands;

use Bizbozo\AdventOfCode\Solutions\SolutionInterface;
use Bizbozo\AdventOfCode\Traits\UsesInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunSolution extends Command
{

    use UsesInput;

    protected function configure()
    {
        $this->setDescription('Run solution')
            ->setName('run')
            ->addArgument('day', InputArgument::REQUIRED, 'Day')
            ->addArgument('to_day', InputArgument::OPTIONAL, ' to Day');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $day = $input->getArgument('day');
        $fromday = (int)($day == 'today' ? date('j') : $day);
        $toDay = (int)$input->getArgument('to_day') ?: $fromday;

        for ($day = $fromday; $day <= $toDay; $day++) {


            $class = sprintf("Bizbozo\\AdventOfCode\\Year%s\\Day%s\\Solution", $_ENV['YEAR'], $this->leadingZero($day));
            $testInputFilenames = $this->getTestInputFilenames($day);
            $inputFilename = $this->getInputFilename($day);

            /** @var SolutionInterface $solution */
            $solution = new $class;


            $style->section(chop($solution->getTitle()));
            if (file_exists($testInputFilenames[0])) {
                if (file_exists($testInputFilenames[1])) {
                    $solution->solve(file_get_contents($testInputFilenames[0]), file_get_contents($testInputFilenames[1]))->output('TEST');
                } else {
                    $solution->solve(file_get_contents($testInputFilenames[0]))->output('TEST');
                }
            }
            if (file_exists($inputFilename)) {
                $solution->solve(file_get_contents($inputFilename))->output('LIVE');
            }
            $style->section('');
        }

        return Command::SUCCESS;

    }

}
