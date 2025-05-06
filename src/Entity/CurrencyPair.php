<?php

namespace App\Entity;

use App\Repository\CurrencyPairRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyPairRepository::class)]
#[ORM\UniqueConstraint(name: 'base_quote_unique', columns: ['base_currency_code', 'quote_currency_code'])]
class CurrencyPair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'base_currency_code', referencedColumnName: 'code', nullable: false)]
    private Currency $baseCurrency;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'quote_currency_code', referencedColumnName: 'code', nullable: false)]
    private Currency $quoteCurrency;

    public function __construct(Currency $baseCurrency, Currency $quoteCurrency)
    {
        $this->baseCurrency = $baseCurrency;
        $this->quoteCurrency = $quoteCurrency;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getQuoteCurrency(): Currency
    {
        return $this->quoteCurrency;
    }

    public function getSymbol(): string
    {
        return $this->baseCurrency->getCode().'/'.$this->quoteCurrency->getCode();
    }
}
