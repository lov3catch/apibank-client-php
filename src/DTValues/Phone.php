<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

class Phone extends DTValue
{
    /**
     * @var mixed
     */
    protected $value;

    public function __construct(string $phone)
    {
        // todo: validations
        $this->value = $phone;
    }
}