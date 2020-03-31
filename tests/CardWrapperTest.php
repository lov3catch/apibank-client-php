<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardOperations;
use ApiBank\DTObjects\CardRequisites;
use ApiBank\DTObjects\CardRequisitesUrl;
use ApiBank\DTObjects\Expire;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\DTObjects\OperationStatusInfo;
use ApiBank\DTObjects\P2pTransfer;
use ApiBank\DTObjects\Transaction;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Wrappers\CardWrapper;
use ApiBank\Wrappers\ProductWrapper;
use Faker\Provider\Uuid;
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

    public function testUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        $fakeAccessToken = new AccessToken('fake-access-token', 0);
        $cardWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $fakeAccessToken))->card();

        $cardWrapper->maskedRequisites($bankCardEan);
    }

    public function testMaskedRequisites()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();
        $cardInfo = $this->cardWrapper->maskedRequisites($bankCardEan);

        $this->assertInstanceOf(CardRequisites::class, $cardInfo);
        $this->assertIsString($cardInfo->getPan());
        $this->assertInstanceOf(Expire::class, $cardInfo->getExpire());
        $this->assertIsString($cardInfo->getExpire()->getMonth());
        $this->assertIsString($cardInfo->getExpire()->getYear());
    }

    public function testIFrameRequisites()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();
        $cardInfo = $this->cardWrapper->IFrameRequisites($bankCardEan);

        $this->assertInstanceOf(CardRequisitesUrl::class, $cardInfo);
        $this->assertIsString($cardInfo->getUrl());
    }

    public function testAccountToCardTransfer()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        global $operationStatus;
        $operationStatus = $this->cardWrapper->transferFromPartnerToClient($bankCardEan, 5.0, Uuid::uuid());

        $this->assertInstanceOf(OperationStatus::class, $operationStatus);
        $this->assertInstanceOf(OperationStatusInfo::class, $operationStatus->getInfo());
        $this->assertIsString($operationStatus->getInfo()->getOperationId());
        $this->assertIsString($operationStatus->getInfo()->getStatus());
        $this->assertIsString($operationStatus->getInfo()->getDescription());

    }

    public function testOperations()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        $periodBegin = (new DateTime())->sub(new DateInterval('P1M'));
        $periodEnd = new DateTime();

        $transactionsInfo = $this->cardWrapper->operations($bankCardEan, $periodBegin, $periodEnd);

        /** @var Transaction $transaction */
        foreach ($transactionsInfo->getTransactions() as $transaction) {
            $this->assertInstanceOf(Transaction::class, $transaction);
        }

        $this->assertInstanceOf(CardOperations::class, $transactionsInfo);
        $this->assertInstanceOf(DateTimeInterface::class, $transactionsInfo->getDateFrom());
        $this->assertInstanceOf(DateTimeInterface::class, $transactionsInfo->getDateTo());
    }

    public function testP2pTransfer()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        $p2pTransferInfo = $this->cardWrapper->p2pTransfer($bankCardEan, 'http://example.com');

        $this->assertInstanceOf(P2pTransfer::class, $p2pTransferInfo);
        $this->assertIsString($p2pTransferInfo->getPaymentPageUrl());
        $this->assertIsString($p2pTransferInfo->getTransactionId());
    }
}
