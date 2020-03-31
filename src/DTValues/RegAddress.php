<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class RegAddress extends DTValue
{
    public function __construct(string $regAddress)
    {
        // todo: validations
        $this->value = $regAddress;
    }
}