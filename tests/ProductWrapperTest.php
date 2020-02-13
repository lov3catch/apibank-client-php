<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Product;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Wrappers\ProductWrapper;
use PHPUnit\Framework\TestCase;

class ProductWrapperTest extends TestCase
{
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

        $this->productWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $authManager->generate()->getAccessToken()))->products();
    }

    public function testUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        global $newBankClient;

        $fakeAccessToken = new AccessToken('fake-access-token', 0);
        $productWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $fakeAccessToken))->products();

        $productWrapper->read($newBankClient->getId());
    }

    public function testRead()
    {
        global $newBankClient;
        $userProduct = $this->productWrapper->read($newBankClient->getId());

        $this->assertInstanceOf(Product::class, $userProduct);
    }
}
