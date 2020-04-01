<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Bank;
use ApiBank\DTObjects\Card;
use ApiBank\DTObjects\Currency;
use ApiBank\DTObjects\Expire;
use ApiBank\DTObjects\Product;
use ApiBank\DTObjects\Status;
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

        /**
         * @var Card
         */
        $card = $userProduct->getCards()->current();

        $this->assertInstanceOf(Card::class, $card);

        $this->assertIsInt($card->getId());
        $this->assertIsString($card->getEan());
        $this->assertIsBool($card->isVirtual());

        $this->assertInstanceOf(Bank::class, $card->getBank());
        $this->assertIsString($card->getBank()->getMachineName());
        $this->assertIsString($card->getBank()->getHumanName());

        $this->assertIsString($card->getMaskedNumber());

        $this->assertInstanceOf(Expire::class, $card->getExpire());
        $this->assertIsString($card->getExpire()->getMonth());
        $this->assertIsString($card->getExpire()->getYear());

        $this->assertInstanceOf(Currency::class, $card->getCurrency());
        $this->assertIsString($card->getCurrency()->code());
        $this->assertIsInt($card->getCurrency()->number());

        $this->assertInstanceOf(Status::class, $card->getStatus());
        $this->assertIsString($card->getStatus()->getCode());
        $this->assertIsString($card->getStatus()->getDisplayName());

        $this->assertIsFloat($card->getBalance());
    }
}
