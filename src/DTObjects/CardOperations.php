<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class CardOperations
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getEan(): string
    {
        return $this->getValue('ean');
    }

    public function getBalance(): float
    {
        return $this->getValue('balance');
    }

    public function getTotalElements(): int
    {
        return $this->getValue('total_elements');
    }

    public function getTransactions(): iterable
    {
        throw new \Exception('Complete me!');
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        $date = $this->getValue('date_from');

        return \DateTimeImmutable::createFromFormat('d.m.Y', $date);
    }

    public function getDateTo(): \DateTimeImmutable
    {
        $date = $this->getValue('date_to');

        return \DateTimeImmutable::createFromFormat('d.m.Y', $date);
    }
}