<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTValues\Phone;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use function Formapro\Values\set_values;

class ClientWrapper
{
    /**
     * @var Client
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

    public function create(Phone $phone): BankClient
    {
        $headers = ['Authorization' => $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];

        $data = [
            'bank_code' => 'AKBARS',
            'phone'     => $phone->value(),
        ];

        $options = [
            'body'    => json_encode($data),
            'headers' => $headers,
        ];

        // todo: validate and throw exception if something went wrong
        // todo: throw exception if user exists
        $bankClientId = json_decode($this->client->post('clients/anonymous', $options)->getBody()->getContents(), true)['client_id'];
        return $this->read($bankClientId);
    }

    public function read(int $bankClientId): BankClient
    {
        $headers = ['Authorization' => $this->accessToken->asBearer()];

        $options = [
            'headers' => $headers,
        ];

        $response = $this->client->get('clients/' . $bankClientId, $options);

        // todo: throw exception
        if (200 !== $response->getStatusCode()) return new Response($response->getStatusCode());

        $clientInfo = json_decode($response->getBody()->getContents(), true);

        $bankClient = new BankClient();
        set_values($bankClient, $clientInfo);

        return $bankClient;
    }

    public function upgrade(User $user, UpgradeAnonymousClientRequest $request): ResponseInterface
    {
        // todo: complete me
        $jsonData = $request->data();
        $headers = ['Authorization' => $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];

        $options = [
            'body'    => json_encode($jsonData),
            'headers' => $headers,
        ];

        return $this->client->post('clients/' . $user->banking()->id() . '/upgrade-account-level-to-uprid', $options);
    }
}