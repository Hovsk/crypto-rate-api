<?php

namespace App\Dto;

use App\Entity\CurrencyPair;

final readonly class RateQueryDto
{
    public function __construct(
        public CurrencyPair $pair,
        public ?\DateTimeImmutable $from = null,
        public ?\DateTimeImmutable $to = null,
    ) {
    }
}
