<?php

namespace App\Repository;

use App\Entity\CurrencyPair;
use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeRate>
 */
class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function existsFor(CurrencyPair $pair, \DateTimeImmutable $timestamp): bool
    {
        return null !== $this->createQueryBuilder('r')
                ->select('1')
                ->where('r.currencyPair = :pair')
                ->andWhere('r.timestamp = :timestamp')
                ->setParameter('pair', $pair)
                ->setParameter('timestamp', $timestamp)
                ->getQuery()
                ->getOneOrNullResult();
    }

    /**
     * @return array<ExchangeRate>
     */
    public function findByPairAndRange(CurrencyPair $pair, ?\DateTimeImmutable $from, ?\DateTimeImmutable $to): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.currencyPair = :pair')
            ->setParameter('pair', $pair)
            ->orderBy('r.timestamp', 'ASC');

        if ($from) {
            $qb->andWhere('r.timestamp >= :from')
                ->setParameter('from', $from);
        }

        if ($to) {
            $qb->andWhere('r.timestamp <= :to')
                ->setParameter('to', $to);
        }

        return $qb->getQuery()->getResult();
    }
}
