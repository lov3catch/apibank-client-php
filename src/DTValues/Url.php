<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Url extends DTValue
{
    public function __construct(string $url)
    {
        // todo: validations
        $this->value = $url;
    }
}