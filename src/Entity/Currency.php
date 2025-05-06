<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 10, unique: true)]
    private string $code;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $name;

    public function __construct(string $code, string $name)
    {
        $this->code = strtoupper($code);
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
