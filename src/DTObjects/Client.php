<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Client
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

    public function getPhone(): string
    {
        return $this->getValue('phone');
    }

    public function isAnonymous(): bool
    {
        return 'ANONYMOUS' === strtoupper($this->getValue('account_level'));
    }

    // todo: return DateTime
    public function getCreated(): string
    {
        return $this->getValue('created');
    }

    // todo: return DateTime
    public function getUpdated(): string
    {
        return $this->getValue('updated');
    }
}