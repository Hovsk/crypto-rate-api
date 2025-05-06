<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\CurrencyPair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyPair>
 */
class CurrencyPairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyPair::class);
    }

    public function findOneByCodes(string $base, string $quote): ?CurrencyPair
    {
        return $this->createQueryBuilder('p')
            ->join('p.baseCurrency', 'base')
            ->join('p.quoteCurrency', 'quote')
            ->where('base.code = :base')
            ->andWhere('quote.code = :quote')
            ->setParameter('base', $base)
            ->setParameter('quote', $quote)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCurrencies(Currency $base, Currency $quote): ?CurrencyPair
    {
        return $this->findOneBy([
            'baseCurrency' => $base,
            'quoteCurrency' => $quote,
        ]);
    }

    public function create(Currency $base, Currency $quote): CurrencyPair
    {
        return new CurrencyPair($base, $quote);
    }
}
