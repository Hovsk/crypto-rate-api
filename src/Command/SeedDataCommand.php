<?php

namespace App\Command;

use App\Repository\CurrencyPairRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seeds base currencies and BTC currency pairs'
)]
class SeedDataCommand extends Command
{
    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
        private readonly CurrencyPairRepository $pairRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly string $baseCurrencyCode,
        private readonly array $fiatCurrencyCodes,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $currencies = $this->persistCurrencies($io);
        $this->persistPairs($currencies, $io);

        $this->entityManager->flush();
        $io->success('Seeded currencies and currency pairs successfully.');

        return Command::SUCCESS;
    }

    private function persistPairs(array $currencies, SymfonyStyle $io): void
    {
        $base = $currencies[$this->baseCurrencyCode];

        foreach ($this->fiatCurrencyCodes as $quoteCode) {
            $quote = $currencies[$quoteCode];
            $pair = $this->pairRepository->findByCurrencies($base, $quote);

            if ($pair) {
                $io->writeln("Pair already exists: {$this->baseCurrencyCode}/{$quoteCode}");
                continue;
            }

            $pair = $this->pairRepository->create($base, $quote);
            $this->entityManager->persist($pair);
            $io->writeln("Created pair: {$this->baseCurrencyCode}/{$quoteCode}");
        }
    }

    private function persistCurrencies(SymfonyStyle $io): array
    {
        $currencies = [];

        foreach (array_merge([$this->baseCurrencyCode], $this->fiatCurrencyCodes) as $code) {
            $currency = $this->currencyRepository->findByCode($code);

            if ($currency) {
                $io->writeln("Currency already exists: {$code}");
            } else {
                $currency = $this->currencyRepository->create($code, $code);
                $this->entityManager->persist($currency);
                $io->writeln("Inserted currency: {$code}");
            }

            $currencies[$code] = $currency;
        }

        return $currencies;
    }
}
