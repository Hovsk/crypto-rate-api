<?php

namespace App\Service;

use App\Dto\RateQueryDto;
use App\Entity\CurrencyPair;
use App\Repository\CurrencyPairRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class RateQueryResolver
{
    public function __construct(
        private CurrencyPairRepository $pairRepository,
    ) {
    }

    public function resolve(Request $request): RateQueryDto
    {
        $pairParam = $request->query->get('pair');
        if (!$pairParam || !str_contains($pairParam, '/')) {
            throw new BadRequestHttpException('Missing or invalid "pair". Use format BTC/USD');
        }

        [$base, $quote] = explode('/', strtoupper($pairParam));

        $from = self::parseHour($request->query->get('from'));
        $to = self::parseHour($request->query->get('to'));

        if (
            ($request->query->get('from') && !$from)
            || ($request->query->get('to') && !$to)
        ) {
            throw new BadRequestHttpException('Invalid date format. Use YYYY-MM-DDTHH');
        }

        $pair = $this->pairRepository->findOneByCodes($base, $quote);
        if (!$pair instanceof CurrencyPair) {
            throw new NotFoundHttpException('Currency pair not found.');
        }

        return new RateQueryDto($pair, $from, $to);
    }

    private static function parseHour(?string $input): ?\DateTimeImmutable
    {
        return $input ? \DateTimeImmutable::createFromFormat('Y-m-d\TH', $input) ?: null : null;
    }
}
