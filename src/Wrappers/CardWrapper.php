<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardOperations;
use ApiBank\DTObjects\CardRequisitesUrl;
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

    public function read(string $bankCardEan): CardRequisitesUrl
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
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }

    public function operations(string $bankCardEan, \DateTimeInterface $periodBegin, \DateTimeInterface $periodEnd): CardOperations
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'query'   => [
                'periodBegin' => $periodBegin->format('d.m.Y'),
                'periodEnd'   => $periodEnd->format('d.m.Y'),
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
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }

    public function p2pTransfer(string $bankCardEan, string $successPageUrl): P2pTransfer
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
            'Content-Type'  => 'application/json',
        ];

        $options = [
            'body'    => json_encode(['successPageUrl' => $successPageUrl]),
            'headers' => $headers,
        ];

        try {
            $response = $this->client->post('cards/' . $bankCardEan . '/p2p-webpage', $options);

            $p2pTransferInfo = json_decode($response->getBody()->getContents(), true);

            $p2pTransfer = new P2pTransfer();
            set_values($p2pTransfer, $p2pTransferInfo);

            return $p2pTransfer;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }
}