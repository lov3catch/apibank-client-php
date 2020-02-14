<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Transaction
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getAmount(): float
    {
        return $this->getValue('amount');
    }

    public function getCurrency(): string
    {
        return $this->getValue('currency');
    }

    public function getDetails(): string
    {
        return $this->getValue('details');
    }

    public function getDocDate(): \DateTimeImmutable
    {
        // todo: complete me!
        // return $this->getValue('doc_date');

        return new \DateTimeImmutable();
    }

    public function getDocNumber(): string
    {
        return $this->getValue('doc_number');
    }

    public function getOwnerName(): string
    {
        return $this->getValue('owner_name');
    }

    public function getStatus(): int
    {
        return $this->getValue('status');
    }

    public function getMcc(): string
    {
        return $this->getValue('mcc');
    }
}