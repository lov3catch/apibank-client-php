<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Passport extends DTValue
{
    public function __construct(string $passport)
    {
        // todo: validations
        $this->value = $passport;
    }
}