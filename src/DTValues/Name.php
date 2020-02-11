<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Name extends DTValue
{
    public function __construct(string $name)
    {
        // todo: validations
        $this->value = $name;
    }
}