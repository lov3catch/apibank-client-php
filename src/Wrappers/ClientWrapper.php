<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTValues\Birthdate;
use ApiBank\DTValues\ControlInfo;
use ApiBank\DTValues\Name;
use ApiBank\DTValues\Passport;
use ApiBank\DTValues\PassportDate;
use ApiBank\DTValues\Patronymic;
use ApiBank\DTValues\Phone;
use ApiBank\DTValues\Snils;
use ApiBank\DTValues\Surname;
use ApiBank\Factories\ExceptionFactory;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
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

        $response = $this->client->post('clients/anonymous', $options);

        if (200 !== $response->getStatusCode()) throw (new ExceptionFactory())->fromResponse($response);

        return $this->read(json_decode($response->getBody()->getContents(), true)['client_id']);
    }

    public function read(int $bankClientId): BankClient
    {
        $headers = ['Authorization' => $this->accessToken->asBearer()];

        $options = [
            'headers' => $headers,
        ];

        $response = $this->client->get('clients/' . $bankClientId, $options);

        if (200 !== $response->getStatusCode()) throw (new ExceptionFactory())->fromResponse($response);

        $clientInfo = json_decode($response->getBody()->getContents(), true);

        $bankClient = new BankClient();
        set_values($bankClient, $clientInfo);

        return $bankClient;
    }

    public function upgrade(
        int $bankClientId,
        Surname $surname,
        Name $name,
        Patronymic $patronymic,
        Passport $passport,
        PassportDate $passportDate,
        Birthdate $birthdate,
        Snils $snils,
        ControlInfo $controlInfo
    ): BankClient
    {
        $headers = ['Authorization' => $this->accessToken->asBearer(), 'Content-Type' => 'application/json'];
        $body = [
            'surname'       => $surname->value(),
            'name'          => $name->value(),
            'patronymic'    => $patronymic->value(),
            'passport'      => $passport->value(),
            'passport_date' => $passportDate->value(),
            'birthdate'     => $birthdate->value(),
            'snils'         => $snils->value(),
            'control_info'  => $controlInfo->value(),
        ];

        $options = [
            'body'    => json_encode($body),
            'headers' => $headers,
        ];

        $response = $this->client->post('clients/' . $bankClientId . '/upgrade-account-level-to-uprid', $options);

        if (200 !== $response->getStatusCode()) throw (new ExceptionFactory())->fromResponse($response);

        return $this->read(json_decode($response->getBody()->getContents(), true)['client_id']);
    }
}