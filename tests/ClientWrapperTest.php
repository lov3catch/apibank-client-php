<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\DTValues\BirthCity;
use ApiBank\DTValues\BirthCountry;
use ApiBank\DTValues\Birthdate;
use ApiBank\DTValues\Name;
use ApiBank\DTValues\Passport;
use ApiBank\DTValues\PassportDate;
use ApiBank\DTValues\PassportDiv;
use ApiBank\DTValues\PassportInfo;
use ApiBank\DTValues\Patronymic;
use ApiBank\DTValues\Phone;
use ApiBank\DTValues\PostAddress;
use ApiBank\DTValues\RealAddress;
use ApiBank\DTValues\RegAddress;
use ApiBank\DTValues\Snils;
use ApiBank\DTValues\Surname;
use ApiBank\DTValues\Url;
use ApiBank\Exceptions\DuplicateClientException;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Exceptions\UpgradeClientException;
use ApiBank\Wrappers\ClientWrapper;
use Faker\Provider\Uuid;
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

    public function testUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $fakeAccessToken = new AccessToken('fake-access-token', 0);
        $clientWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $fakeAccessToken))->client();

        $clientWrapper->create(new Phone(getenv('USER_PHONE')));
    }

    public function testCreateClient()
    {
        $clientInfo = $this->clientWrapper->create(new Phone(getenv('USER_PHONE')));

        global $newBankClient;
        $newBankClient = $clientInfo;

        $this->assertInstanceOf(BankClient::class, $clientInfo);

        return $clientInfo;
    }

    /**
     * @depends testCreateClient
     * @param BankClient $bankClient
     * @throws Throwable
     */
    public function testCreateClientDuplicate(BankClient $bankClient)
    {
        $this->expectException(DuplicateClientException::class);

        $this->clientWrapper->create(new Phone($bankClient->getPhone()));
    }

    public function testRead()
    {
        global $newBankClient;

        $clientInfo = $this->clientWrapper->read($newBankClient->getId());

        $this->assertInstanceOf(BankClient::class, $clientInfo);
    }

    public function testUpdateClient()
    {
        global $newBankClient;
        global $randomUniqueString;

        $randomUniqueString = Uuid::uuid();

        $updateInfo = $this->upgradeClient($newBankClient, $randomUniqueString);

        $this->assertInstanceOf(OperationStatus::class, $updateInfo);

        return $updateInfo;
    }

    public function testUpdateClientWitchAlreadyUpgraded()
    {
        global $newBankClient;

        $this->expectException(UpgradeClientException::class);

        $randomUniqueString = Uuid::uuid();

        $this->upgradeClient($newBankClient, $randomUniqueString);
    }

    private function upgradeClient(BankClient $bankClient, string $randomUniqueString)
    {
        return $this->clientWrapper->upgrade(
            $randomUniqueString,
            $bankClient->getId(),
            new Url(getenv('WEBHOOK_URL')),
            new Surname(getenv('USER_SURNAME')),
            new Name(getenv('USER_NAME')),
            new Patronymic(getenv('USER_PATRONYMIC')),
            new Birthdate(getenv('USER_BIRTHDATE')),
            new BirthCountry(getenv('USER_BIRTH_COUNTRY')),
            new BirthCity(getenv('USER_BIRTH_CITY')),
            new Passport(getenv('USER_PASSPORT')),
            new PassportDate(getenv('USER_PASSPORT_DATE')),
            new PassportDiv(getenv('USER_PASSPORT_DIV')),
            new PassportInfo(getenv('USER_PASSPORT_INFO')),
            new RealAddress(getenv('USER_REAL_ADDRESS')),
            new RegAddress(getenv('USER_REG_ADDRESS')),
            new PostAddress(getenv('USER_POST_ADDRESS')),
            new Snils(getenv('USER_SNILS')),
            true,
            true,
            true
        );
    }
}
