<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class OperationStatusInfo
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getOperationId(): string 
    {
        return $this->getValue('operation_id');
    }

    public function getStatus(): string 
    {
        return $this->getValue('status');
    }

    public function getDescription(): string
    {
        return $this->getValue('description');
    }
}