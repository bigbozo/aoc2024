<?php

namespace Bizbozo\AdventOfCode\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunSolution extends AbstractCommand
{
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
        $headlines = file(__DIR__ . '/../../Headlines.txt');

        $day = $input->getArgument('day');
        $fromday=(int)($day=='today' ? date('j') : $day);
        $toDay = (int)$input->getArgument('to_day') ?: $fromday;

        for ($day = $fromday; $day <= $toDay; $day++) {


            $class = 'Bizbozo\AdventOfCode\Day' . $this->leadingZero($day) . '\Solution';
            $testInputFilenames = $this->getTestInputFilenames($day);
            $inputFilename = $this->getInputFilename($day);


            $style->section(chop($headlines[$day - 1]));
            if (file_exists($testInputFilenames[0])) {
                if (file_exists($testInputFilenames[1])) {
                    call_user_func([$class, 'solve'], file_get_contents($testInputFilenames[0]),file_get_contents($testInputFilenames[1]))->output('TEST');
                } else {
                    call_user_func([$class, 'solve'], file_get_contents($testInputFilenames[0]))->output('TEST');
                }
            }
            if (file_exists($inputFilename)) {
                call_user_func([$class, 'solve'], file_get_contents($inputFilename))->output('LIVE');
            }
            $style->section('');
        }

        return Command::SUCCESS;

    }

}
