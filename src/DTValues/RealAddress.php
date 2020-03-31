<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class RealAddress extends DTValue
{
    public function __construct(string $realAddress)
    {
        // todo: validations
        $this->value = $realAddress;
    }
}