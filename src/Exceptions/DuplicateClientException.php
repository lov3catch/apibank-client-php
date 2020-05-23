<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

use ApiBank\Exceptions\Traits\WithResponse;

class DuplicateClientException extends \Exception implements ApiBankException
{
    use WithResponse;

    protected $code = 'CE001';
    protected $message = 'Клиент с указанным номером телефона уже зарегистрирован.';
}