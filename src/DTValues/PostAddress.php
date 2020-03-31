<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class PostAddress extends DTValue
{
    public function __construct(string $postAddress)
    {
        // todo: validations
        $this->value = $postAddress;
    }
}