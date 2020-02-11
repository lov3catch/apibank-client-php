<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\DTObjects\CardRequisitesUrl;
use ApiBank\Wrappers\CardWrapper;
use ApiBank\Wrappers\ProductWrapper;
use PHPUnit\Framework\TestCase;

class CardWrapperTest extends TestCase
{
    /**
     * @var CardWrapper
     */
    private $cardWrapper;
    /**
     * @var ProductWrapper
     */
    private $productWrapper;

    protected function setUp(): void
    {
        $authManager = new AuthManager(
            getenv('APP_ID'),
            getenv('APP_SECRET'),
            getenv('LOGIN'),
            getenv('PASSWORD'),
            getenv('GENERATE_TOKEN_URL'),
            getenv('REFRESH_TOKEN_URL'),
            (bool)getenv('VERIFY_SSL'));

        $apiBank = new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $authManager->generate()->getAccessToken());

        $this->productWrapper = $apiBank->products();
        $this->cardWrapper = $apiBank->card();
    }

    public function testRead()
    {
        $bankCardEan = $this->productWrapper->read(45)->current()->getCards()->current()->getEan();
        $cardInfo = $this->cardWrapper->read($bankCardEan);

        $this->assertInstanceOf(CardRequisitesUrl::class, $cardInfo);
        $this->assertIsString($cardInfo->getUrl());
    }
}
