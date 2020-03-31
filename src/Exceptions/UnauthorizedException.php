<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

class UnauthorizedException extends \Exception
{
    protected $code = 'S000';
    protected $message = 'Пользователь не аутентифицирован.';
}