<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\DTObjects\Product;
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

    public function testRead()
    {
        global $newBankClient;
        $userProducts = $this->productWrapper->read($newBankClient->getId());

        $this->assertInstanceOf(Generator::class, $userProducts);

        foreach ($userProducts as $userProduct) {
            $this->assertInstanceOf(Product::class, $userProduct);
        }
    }
}
