<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class BirthCountry extends DTValue
{
    public function __construct(string $country)
    {
        // todo: validations
        $this->value = $country;
    }
}