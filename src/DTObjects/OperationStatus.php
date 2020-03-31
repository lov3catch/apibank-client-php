<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class OperationStatus
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getInfo(): OperationStatusInfo
    {
        return $this->getObject('info', OperationStatusInfo::class);
    }
}