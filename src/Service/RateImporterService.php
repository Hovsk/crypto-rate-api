<?php

namespace App\Service;

use App\Entity\CurrencyPair;
use App\Entity\ExchangeRate;
use App\Repository\CurrencyPairRepository;
use App\Repository\ExchangeRateRepository;
use App\Service\RateProvider\RateProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RateImporterService
{
    public function __construct(
        private RateProviderInterface $rateProvider,
        private CurrencyPairRepository $pairRepository,
        private ExchangeRateRepository $rateRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function importCurrentRates(): void
    {
        $pairs = $this->pairRepository->findAll();
        if (empty($pairs)) {
            return;
        }

        $timestamp = $this->getCurrentHour();
        $quoteCodes = $this->extractQuoteCodes($pairs);
        $ratesData = $this->rateProvider->getRates($quoteCodes);

        foreach ($pairs as $pair) {
            $this->storeRateIfNew($pair, $ratesData, $timestamp);
        }

        $this->entityManager->flush();
    }

    private function extractQuoteCodes(array $pairs): array
    {
        return array_map(
            static fn ($pair) => strtolower($pair->getQuoteCurrency()->getCode()),
            $pairs
        );
    }

    private function storeRateIfNew(
        CurrencyPair $pair,
        array $data,
        \DateTimeImmutable $timestamp,
    ): void {
        $quoteCode = strtolower($pair->getQuoteCurrency()->getCode());
        $rateValue = $data[$quoteCode] ?? null;

        if (null === $rateValue) {
            return;
        }

        if ($this->rateRepository->existsFor($pair, $timestamp)) {
            return;
        }

        $rate = new ExchangeRate($pair, (string) $rateValue, $timestamp);
        $this->entityManager->persist($rate);
    }

    private function getCurrentHour(): \DateTimeImmutable
    {
        $now = new \DateTimeImmutable();

        return $now->setTime((int) $now->format('H'), 0);
    }
}
