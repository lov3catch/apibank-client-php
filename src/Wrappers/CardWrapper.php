<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\CardRequisitesUrl;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
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

    public function operations(User $user, GetCardOperationsRequest $request): ResponseInterface
    {
        // todo: fixme
        $headers = ['Authorization' => 'Bearer ' . $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];
        $url = 'cards/' . $user->banking()->cardHolder()->first()->ean() . '/account-extract/';
        $params = $request->data();

        $options = [
            'query'   => $params,
            'headers' => $headers,
        ];

        return $this->client->request('GET', $url, $options);
    }

    public function p2pTransfer(User $user, Request $request): ResponseInterface
    {
        // todo: fixme
        $headers = ['Authorization' => 'Bearer ' . $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];
        $url = 'cards/' . $user->banking()->cardHolder()->first()->ean() . '/p2p-webpage';
        $body = ['successPageUrl' => 'http://example.com'];     // todo: опаньки

        $options = [
            'body'    => json_encode($body),
            'headers' => $headers,
        ];

        return $this->client->post($url, $options);
    }
}