<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Currency
{
    use ValuesTrait;
    use ObjectsTrait;

    public function code(): string
    {
        return $this->getValue('code');
    }

    public function number(): string
    {
        return $this->getValue('code_num');
    }
}