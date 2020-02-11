<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardOperations;
use ApiBank\DTObjects\CardRequisitesUrl;
use ApiBank\DTObjects\P2pTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
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
        $headers = ['Authorization' => $this->accessToken->asBearer()];
        $url = 'virtual-cards/' . $bankCardEan . '/page';

        $response = $this->client->request('GET', $url, ['headers' => $headers]);

        // todo: throw exception
        if (200 !== $response->getStatusCode()) return new Response($response->getStatusCode());

        $cardRequisitesUrlInfo = json_decode($response->getBody()->getContents(), true);

        $cardRequisitesUrl = new CardRequisitesUrl();
        set_values($cardRequisitesUrl, $cardRequisitesUrlInfo);

        return $cardRequisitesUrl;
    }

    public function operations(string $bankCardEan, \DateTimeInterface $periodBegin, \DateTimeInterface $periodEnd): CardOperations
    {
        $headers = ['Authorization' => $this->accessToken->asBearer()];
        $url = 'cards/' . $bankCardEan . '/account-extract/';

        $options = [
            'query'   => [
                'periodBegin' => $periodBegin->format('d.m.Y'),
                'periodEnd'   => $periodEnd->format('d.m.Y'),
            ],
            'headers' => $headers,
        ];

        $response = $this->client->request('GET', $url, $options);

        // todo: throw exception
        if (200 !== $response->getStatusCode()) return new Response($response->getStatusCode());

        $cardOperationsInfo = json_decode($response->getBody()->getContents(), true);

        $cardOperations = new CardOperations();
        set_values($cardOperations, $cardOperationsInfo);

        return $cardOperations;
    }

    public function p2pTransfer(string $bankCardEan, string $successPageUrl): P2pTransfer
    {
        $headers = ['Authorization' => $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];
        $url = 'cards/' . $bankCardEan . '/p2p-webpage';

        $options = [
            'body'    => json_encode(['successPageUrl' => $successPageUrl]),
            'headers' => $headers,
        ];

        $response = $this->client->post($url, $options);

        // todo: throw exception
        if (200 !== $response->getStatusCode()) return new Response($response->getStatusCode());

        $p2pTransferInfo = json_decode($response->getBody()->getContents(), true);

        $p2pTransfer = new P2pTransfer();
        set_values($p2pTransfer, $p2pTransferInfo);

        return $p2pTransfer;
    }
}