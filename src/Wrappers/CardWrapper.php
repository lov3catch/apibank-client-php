<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardOperations;
use ApiBank\DTObjects\CardRequisites;
use ApiBank\DTObjects\CardRequisitesUrl;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\DTObjects\P2pTransfer;
use ApiBank\Factories\ExceptionFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use function Formapro\Values\set_values;

class CardWrapper
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var AccessToken
     */
    private $accessToken;

    public function __construct(AccessToken $accessToken, ClientInterface $client)
    {
        $this->accessToken = $accessToken;
        $this->client = $client;
    }

    public function maskedRequisites(string $bankCardEan): CardRequisites
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $response = $this->client->request('GET', 'virtual-cards/' . $bankCardEan, $options);

            $cardRequisitesUrlInfo = json_decode($response->getBody()->getContents(), true);

            $cardRequisites = new CardRequisites();
            set_values($cardRequisites, $cardRequisitesUrlInfo);

            return $cardRequisites;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }

    public function IFrameRequisites(string $bankCardEan): CardRequisitesUrl
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $response = $this->client->request('GET', 'virtual-cards/' . $bankCardEan . '/page', $options);

            $cardRequisitesUrlInfo = json_decode($response->getBody()->getContents(), true);

            $cardRequisitesUrl = new CardRequisitesUrl();
            set_values($cardRequisitesUrl, $cardRequisitesUrlInfo);

            return $cardRequisitesUrl;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }

    public function operations(string $bankCardEan, \DateTimeInterface $periodBegin, \DateTimeInterface $periodEnd): CardOperations
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'query'   => [
                'period_begin' => $periodBegin->format('d.m.Y'),
                'period_end'   => $periodEnd->format('d.m.Y'),
            ],
            'headers' => $headers,
        ];

        try {
            $response = $this->client->request('GET', 'cards/' . $bankCardEan . '/account-extract/', $options);

            $cardOperationsInfo = json_decode($response->getBody()->getContents(), true);

            $cardOperations = new CardOperations();
            set_values($cardOperations, $cardOperationsInfo);

            return $cardOperations;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }

    public function transferFromPartnerToClient(string $bankCardEan, float $amount, string $randomUniqueString): OperationStatus
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
            'Content-Type'  => 'application/json',
        ];

        $options = [
            'body'    => json_encode(['amount' => $amount, 'operation_id' => $randomUniqueString]),
            'headers' => $headers,
        ];

        try {
            $response = $this->client->post('cards/' . $bankCardEan . '/account2card', $options);

            $operationStatusData = json_decode($response->getBody()->getContents(), true);

            $operationStatus = new OperationStatus();
            set_values($operationStatus, $operationStatusData);

            return $operationStatus;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }

    public function p2pTransfer(string $bankCardEan, string $successPageUrl): P2pTransfer
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
            'Content-Type'  => 'application/json',
        ];

        $options = [
            'body'    => json_encode(['success_page_url' => $successPageUrl]),
            'headers' => $headers,
        ];

        try {
            $response = $this->client->post('cards/' . $bankCardEan . '/p2p-webpage', $options);

            $p2pTransferInfo = json_decode($response->getBody()->getContents(), true);

            $p2pTransfer = new P2pTransfer();
            set_values($p2pTransfer, $p2pTransferInfo);

            return $p2pTransfer;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }
}