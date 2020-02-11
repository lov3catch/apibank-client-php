<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Snils extends DTValue
{
    public function __construct(string $snils)
    {
        // todo: validations
        $this->value = $snils;
    }
}