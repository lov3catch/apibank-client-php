<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Phone extends DTValue
{
    public function __construct(string $phone)
    {
        // todo: validations
        $this->value = $phone;
    }
}