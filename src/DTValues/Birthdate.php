<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Birthdate extends DTValue
{
    public function __construct(string $birthday)
    {
        // todo: validations
        $this->value = $birthday;
    }
}