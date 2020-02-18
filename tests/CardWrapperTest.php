<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\AuthManager;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardOperations;
use ApiBank\DTObjects\CardRequisitesUrl;
use ApiBank\DTObjects\P2pTransfer;
use ApiBank\DTObjects\Transaction;
use ApiBank\Exceptions\UnauthorizedException;
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

    public function testUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        $fakeAccessToken = new AccessToken('fake-access-token', 0);
        $cardWrapper = (new ApiBank(getenv('API_URL'), (bool)getenv('VERIFY_SSL'), $fakeAccessToken))->card();

        $cardWrapper->read($bankCardEan);
    }

    public function testRead()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();
        $cardInfo = $this->cardWrapper->read($bankCardEan);

        $this->assertInstanceOf(CardRequisitesUrl::class, $cardInfo);
        $this->assertIsString($cardInfo->getUrl());
    }

    public function testAccountToCardTransfer()
    {
        global $newBankClient;

        $bankCardEan = $this->productWrapper->read($newBankClient->getId())->getCards()->current()->getEan();

        $isComplete = $this->cardWrapper->transferFromPartnerToClient($bankCardEan, 110.0);

        $this->assertTrue($isComplete);
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
        $this->assertIsString($p2pTransferInfo->getOperationId());
    }
}
