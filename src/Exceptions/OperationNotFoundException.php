<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

class OperationNotFoundException extends \Exception
{
    protected $code = 'F003';
    protected $message = 'Операция с указанным ID не найдена.';
}