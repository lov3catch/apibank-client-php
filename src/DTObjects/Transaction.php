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

    public function getCurrency(): Currency
    {
        return $this->getObject('currency', Currency::class);
    }

    public function getDescription(): string
    {
        return $this->getValue('description');
    }

    public function getOperationDate(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:sT', $this->getValue('operation_date'));
    }

    public function getOperationNumber(): string
    {
        return $this->getValue('operation_number');
    }

    public function getMerchantName(): string
    {
        return $this->getValue('merchant_name');
    }

    public function getFinStatus(): bool
    {
        return $this->getValue('fin_status');
    }

    public function getDebet(): bool
    {
        return $this->getValue('debet');
    }

    public function getMcc(): string
    {
        return $this->getValue('mcc');
    }

    public function getMerchantId(): string
    {
        return $this->getValue('merchant_id');
    }
}