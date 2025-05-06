<?php

namespace App\Service\RateProvider;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class CoinGeckoRateProvider implements RateProviderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiUrl,
        private string $assetId,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getRates(array $vsCurrencies): array
    {
        $url = rtrim($this->apiUrl, '/').'/simple/price';

        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'ids' => $this->assetId,
                'vs_currencies' => implode(',', array_unique($vsCurrencies)),
            ],
        ]);

        $data = $response->toArray();

        return $data[$this->assetId] ?? [];
    }
}
