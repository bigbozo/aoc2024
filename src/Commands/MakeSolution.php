<?php

namespace Bizbozo\AdventOfCode\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeSolution extends AbstractCommand
{
    protected function configure()
    {
        $this->setDescription('Create a stub for day x of AdventOfCode')
            ->setName('generate')
            ->addArgument('day', InputArgument::REQUIRED, 'Day');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $day = (int)$input->getArgument('day');

        $this->genreateSolution($day);

        if ($day < 1 || $day > 25) {
            $output->writeln(['Day out of range']);
            return Command::FAILURE;
        }

        if (date('d') < $day) {
            $output->writeln(['Input-File not ready. You are too early',]);
            return Command::FAILURE;
        }

        $inputFilename = $this->getInputFilename($day);
        $testInputFilenames = $this->getTestInputFilenames($day);
        if (file_exists($inputFilename)) {
            $output->writeln(['Input-File exists. Aborting',]);
            return Command::FAILURE;
        } else {
            try {
                $data = $this->fetchInputData($day);
                if ($data) {
                    file_put_contents($inputFilename, $data);
                }
                touch($testInputFilenames[0]);
            } catch (GuzzleException $e) {
                $output->writeln([$e->getMessage()]);
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;

    }


    /**
     * @throws GuzzleException
     */
    private function fetchInputData(int $day): string
    {
        $client = new Client();

        if (!$_ENV['APIKEY']) {
            return false;
        }

        $jar = CookieJar::fromArray(
            [
                'session' => $_ENV['APIKEY'],
            ],
            'adventofcode.com'
        );

        $res = $client->request('GET', sprintf("https://adventofcode.com/%s/day/%d/input", $_ENV['YEAR'], $day), [
            'cookies' => $jar
        ]);
        return $res->getBody()->getContents();
    }

    private function getSolutionFilename(string $year, int $day)
    {
        return sprintf("%s/../%s/Day%s/Solution.php", __DIR__, $year, $this->leadingZero($day));
    }

    private function genreateSolution($day)
    {
        $template = file_get_contents(__DIR__ . '/../../templates/Solution.template');
        $code = strtr(
            $template,
            [
                '###day###' => $day,
                '###DAY###' => $this->leadingZero($day),
                '###YEAR###' => $_ENV['YEAR']
            ]
        );
        $filename = $this->getSolutionFilename($_ENV['YEAR'], $day);
        if (!file_exists($filename)) {
            mkdir(dirname($filename), recursive: true);
            file_put_contents($filename, $code);
        }
    }


}
