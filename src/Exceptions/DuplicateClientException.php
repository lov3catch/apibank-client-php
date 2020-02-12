<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

class DuplicateClientException extends \Exception
{
    protected $code = 'CE001';
    protected $message = 'Клиент с указанным номером телефона уже зарегистрирован.';
}