<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class Expire
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getMonth(): string
    {
        return $this->getValue('month');
    }

    public function getYear(): string
    {
        return $this->getValue('year');
    }
}