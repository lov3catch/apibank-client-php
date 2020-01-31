<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Product
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getId(): int
    {
        return $this->getValue('id');
    }

    public function getBank(): Bank
    {
        return $this->getObject('bank', Bank::class);
    }

    public function isAnonymous(): bool
    {
        return 'ANONYMOUS' === strtoupper($this->getValue('account_level'));
    }

    public function getBalance(): float
    {
        return $this->getValue('balance');
    }

    public function getCards(): \Generator
    {
        return $this->getObjects('cards', Card::class);
    }
}