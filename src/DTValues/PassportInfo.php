<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class PassportInfo extends DTValue
{
    public function __construct(string $passportInfo)
    {
        // todo: validations
        $this->value = $passportInfo;
    }
}