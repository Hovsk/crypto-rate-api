<?php

namespace App\Command;

use App\Service\RateImporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-rates',
    description: 'Fetches current exchange rates from external API and stores them'
)]
final class FetchRatesCommand extends Command
{
    public function __construct(
        private readonly RateImporterService $rateImporter,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->rateImporter->importCurrentRates();
            $io->success('Rates fetched and stored successfully.');
        } catch (\Throwable $e) {
            $io->error('Failed to fetch rates: '.$e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
