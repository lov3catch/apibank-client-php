<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

use ApiBank\Exceptions\Traits\WithResponse;

class UnauthorizedException extends \Exception implements ApiBankException
{
    use WithResponse;

    protected $code = 'S000';
    protected $message = 'Пользователь не аутентифицирован.';
}