<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Wrappers\CardWrapper;
use ApiBank\Wrappers\ClientWrapper;
use ApiBank\Wrappers\ProductWrapper;
use PHPUnit\Framework\TestCase;

class ApiBankTest extends TestCase
{
    /**
     * @var ApiBank
     */
    private $apibank;

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

        $this->apibank = new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $authManager->generate()->getAccessToken());
    }

    public function testCard()
    {
        $this->assertInstanceOf(CardWrapper::class, $this->apibank->card());
    }

    public function testProducts()
    {
        $this->assertInstanceOf(ProductWrapper::class, $this->apibank->products());
    }

    public function testClient()
    {
        $this->assertInstanceOf(ClientWrapper::class, $this->apibank->client());
    }
}
