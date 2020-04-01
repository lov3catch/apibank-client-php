<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Status
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getCode(): string
    {
        return $this->getValue('code');
    }

    public function getDisplayName(): string
    {
        return $this->getValue('display_name');
    }
}