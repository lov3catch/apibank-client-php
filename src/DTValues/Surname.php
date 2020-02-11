<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Surname extends DTValue
{
    public function __construct(string $surname)
    {
        // todo: validations
        $this->value = $surname;
    }
}