<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

use ApiBank\Exceptions\Traits\WithResponse;

class OperationNotFoundException extends \Exception implements ApiBankException
{
    use WithResponse;

    protected $code = 'F003';
    protected $message = 'Операция с указанным ID не найдена.';
}