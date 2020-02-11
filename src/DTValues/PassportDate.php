<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class PassportDate extends DTValue
{
    public function __construct(string $passportDate)
    {
        // todo: validations
        $this->value = $passportDate;
    }
}