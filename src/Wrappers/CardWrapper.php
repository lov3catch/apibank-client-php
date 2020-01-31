<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

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

    public function read(string $bankCardEan): ResponseInterface
    {
        // todo: fixme
        $headers = ['Authorization' => $this->accessToken->asBearer()];
        $url = 'virtual_cards/' . $bankCardEan . '/page';

        return $this->client->request('GET', $url, ['headers' => $headers]);
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