<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

use ApiBank\Exceptions\Traits\WithResponse;

class DefaultException extends \Exception implements ApiBankException
{
    use WithResponse;
}