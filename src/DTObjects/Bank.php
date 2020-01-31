<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Bank
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getMachineName(): string
    {
        return $this->getValue('bank_code');
    }

    public function getHumanName(): string
    {
        return $this->getValue('display_name');
    }
}