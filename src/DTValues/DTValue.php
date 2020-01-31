<?php

declare(strict_types=1);

namespace ApiBank\DTValues;

abstract class DTValue
{
    /**
     * @var mixed
     */
    protected $value;

    public function value()
    {
        return $this->value;
    }
}