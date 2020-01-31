<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTValues\Phone;
use ApiBank\Wrappers\ClientWrapper;
use PHPUnit\Framework\TestCase;

class ClientWrapperTest extends TestCase
{
    /**
     * @var ClientWrapper
     */
    private $clientWrapper;

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

        $this->clientWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $authManager->generate()->getAccessToken()))->client();
    }

    public function testCreate()
    {
        $clientInfo = $this->clientWrapper->create(new Phone(getenv('USER_PHONE')));

        $this->assertInstanceOf(BankClient::class, $clientInfo);
    }

    public function testRead()
    {
        $clientInfo = $this->clientWrapper->read(45);

        $this->assertInstanceOf(BankClient::class, $clientInfo);
    }
}
