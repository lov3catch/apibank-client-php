<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\Exceptions\OperationNotFoundException;
use ApiBank\Exceptions\UnauthorizedException;
use Faker\Provider\Uuid;
use PHPUnit\Framework\TestCase;

class OperationWrapperTest extends TestCase
{
    /**
     * @var OperationWrapper
     */
    private $operationWrapper;

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

        $this->operationWrapper = $apiBank->operations();
    }

    public function testUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $fakeAccessToken = new AccessToken('fake-access-token', 0);
        $operationWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $fakeAccessToken))->operations();

        $operationWrapper->getStatus('invalid-operaion-id');
    }

    public function testOperationStatusWithInvalidOperationId()
    {
        $this->expectException(OperationNotFoundException::class);

        $this->operationWrapper->getStatus(Uuid::uuid());
    }

    public function testOperationStatusWithValidOperationId()
    {
        /**
         * @var $operationStatus OperationStatus
         */
        global $operationStatus;

        $operationStatus = $this->operationWrapper->getStatus($operationStatus->getInfo()->getOperationId());

        $this->assertInstanceOf(OperationStatus::class, $operationStatus);
    }
}
