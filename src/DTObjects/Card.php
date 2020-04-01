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
        return $this->getValue('card_id');
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

    public function getMaskedNumber(): string
    {
        return $this->getValue('masked_number');
    }

    public function getExpire(): Expire
    {
        return $this->getObject('expire', Expire::class);
    }

    public function getCurrency(): Currency
    {
        return $this->getObject('currency', Currency::class);
    }

    public function getStatus(): Status
    {
        return $this->getObject('status', Status::class);
    }

    public function getBalance(): float
    {
        return $this->getValue('balance');
    }
}