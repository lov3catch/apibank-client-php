<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class CardRequisites
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getPan(): string
    {
        return $this->getValue('pan');
    }

    public function getExpire(): Expire
    {
        return $this->getObject('expire', Expire::class);
    }
}