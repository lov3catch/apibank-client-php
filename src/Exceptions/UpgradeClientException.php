<?php

declare(strict_types=1);

namespace ApiBank\Exceptions;

use ApiBank\Exceptions\Traits\WithResponse;

class UpgradeClientException extends \Exception implements ApiBankException
{
    use WithResponse;

    protected $code = 'CE002';
    protected $message = 'У Клиента с указанным номером паспорта уже зарегистрирован УПРИД счет или УПРИД счет находится в процессе создания.';
}