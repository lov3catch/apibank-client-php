<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class ControlInfo extends DTValue
{
    public function __construct(string $controlInfo)
    {
        // todo: validations
        $this->value = $controlInfo;
    }
}