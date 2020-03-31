<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class BirthCity extends DTValue
{
    public function __construct(string $city)
    {
        // todo: validations
        $this->value = $city;
    }
}