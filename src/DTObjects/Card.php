<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Card
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getId(): int
    {
        return $this->getValue('id');
    }

    public function getEan(): string
    {
        return $this->getValue('ean');
    }

    public function isVirtual(): bool
    {
        return $this->getValue('is_virtual');
    }

    public function getBank(): Bank
    {
        return $this->getObject('bank', Bank::class);
    }

    public function getExpire(): array
    {
        return $this->getValue('expire');
    }

    public function getCurrency(): string
    {
        return $this->getValue('currency');
    }

    public function getCurrencyCode(): int
    {
        return $this->getValue('currency_code');
    }

    public function getBalance(): float
    {
        return $this->getValue('balance');
    }
}