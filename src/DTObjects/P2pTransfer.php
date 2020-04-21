<?php

declare(strict_types=1);

namespace ApiBank\DTObjects;

use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;

class P2pTransfer
{
    use ValuesTrait;
    use ObjectsTrait;

    public function getPaymentPageUrl(): string
    {
        return $this->getValue('payment_page_url');
    }

    public function getTransactionId(): string
    {
        return $this->getValue('operation_id');
    }
}