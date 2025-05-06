<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
#[ORM\Table(name: 'exchange_rate')]
#[ORM\UniqueConstraint(name: 'pair_time_unique', columns: ['currency_pair_id', 'timestamp'])]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CurrencyPair::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CurrencyPair $currencyPair;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 8)]
    private string $rate;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $timestamp;

    public function __construct(CurrencyPair $currencyPair, string $rate, \DateTimeImmutable $timestamp)
    {
        $this->currencyPair = $currencyPair;
        $this->rate = $rate;
        $this->timestamp = $timestamp;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyPair(): CurrencyPair
    {
        return $this->currencyPair;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
