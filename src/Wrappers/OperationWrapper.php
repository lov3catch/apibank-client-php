<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\Factories\ExceptionFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use function Formapro\Values\set_values;

class OperationWrapper
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

    /**
     * @param string $randomUniqueString
     * @return OperationStatus
     * @throws GuzzleException
     * @throws Throwable
     */
    public function getStatus(string $randomUniqueString): OperationStatus
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $response = $this->client->request('GET', 'operations/status/' . $randomUniqueString, $options);

            $operationStatusData = json_decode($response->getBody()->getContents(), true);

            $operationStatus = new OperationStatus();
            set_values($operationStatus, $operationStatusData);

            return $operationStatus;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }
}