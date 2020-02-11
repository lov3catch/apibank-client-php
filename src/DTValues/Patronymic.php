<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Patronymic extends DTValue
{
    public function __construct(string $patronymic)
    {
        // todo: validations
        $this->value = $patronymic;
    }
}