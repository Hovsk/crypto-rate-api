<?php

namespace App\Controller;

use App\Service\ExchangeRateService;
use App\Service\RateQueryResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController extends AbstractController
{
    public function __construct(
        private readonly RateQueryResolver $resolver,
        private readonly ExchangeRateService $service,
    ) {
    }

    #[Route('/api/rates', name: 'api_rates', methods: ['GET'])]
    public function getRates(
        Request $request,
    ): JsonResponse {
        try {
            $dto = $this->resolver->resolve($request);
            $data = $this->service->getRates($dto);

            return $this->json([
                'pair' => $dto->pair->getSymbol(),
                'data' => $data,
            ]);
        } catch (HttpExceptionInterface $e) {
            return $this->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Throwable $e) {
            // TODO::need to log exception
            return $this->json(['error' => 'something went wrong'], 500);
        }
    }
}
