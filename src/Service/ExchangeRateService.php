<?php

namespace App\Service;

use App\Dto\RateQueryDto;
use App\Repository\ExchangeRateRepository;

final readonly class ExchangeRateService
{
    public function __construct(
        private ExchangeRateRepository $rateRepository,
    ) {
    }

    public function getRates(RateQueryDto $dto): array
    {
        $rates = $this->rateRepository->findByPairAndRange(
            $dto->pair,
            $dto->from,
            $dto->to,
        );

        return array_map(
            fn ($rate) => [
                'timestamp' => $rate->getTimestamp()->format(\DateTimeInterface::ATOM),
                'rate' => $rate->getRate(),
            ],
            $rates,
        );
    }
}
