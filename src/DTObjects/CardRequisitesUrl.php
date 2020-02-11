<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class CardRequisitesUrl
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getUrl(): string
    {
        return $this->getValue('url');
    }
}