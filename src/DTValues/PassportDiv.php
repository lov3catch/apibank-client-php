<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class PassportDiv extends DTValue
{
    public function __construct(string $passportDiv)
    {
        // todo: validations
        $this->value = $passportDiv;
    }
}