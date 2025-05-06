<?php

namespace App\Service\RateProvider;

interface RateProviderInterface
{
    public function getRates(array $vsCurrencies): array;
}
