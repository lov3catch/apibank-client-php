<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTValues\Birthdate;
use ApiBank\DTValues\ControlInfo;
use ApiBank\DTValues\Name;
use ApiBank\DTValues\Passport;
use ApiBank\DTValues\PassportDate;
use ApiBank\DTValues\Patronymic;
use ApiBank\DTValues\Phone;
use ApiBank\DTValues\Snils;
use ApiBank\DTValues\Surname;
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

        global $newBankClient;
        $newBankClient = $clientInfo;

        $this->assertInstanceOf(BankClient::class, $clientInfo);
    }

    public function testRead()
    {
        global $newBankClient;

        $clientInfo = $this->clientWrapper->read($newBankClient->getId());

        $this->assertInstanceOf(BankClient::class, $clientInfo);
    }

    public function testUpdate()
    {
        global $newBankClient;

        $updateInfo = $this->clientWrapper->upgrade(
            $newBankClient->getId(),
            new Surname(getenv('USER_SURNAME')),
            new Name(getenv('USER_NAME')),
            new Patronymic(getenv('USER_PATRONYMIC')),
            new Passport(getenv('USER_PASSPORT')),
            new PassportDate(getenv('USER_PASSPORT_DATE')),
            new Birthdate(getenv('USER_BIRTHDATE')),
            new Snils(getenv('USER_SNILS')),
            new ControlInfo(getenv('USER_CONTROL_INFO'))
        );

        $this->assertInstanceOf(BankClient::class, $updateInfo);
    }
}
