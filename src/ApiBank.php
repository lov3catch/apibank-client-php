<?php

declare(strict_types=1);

namespace ApiBank;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\Wrappers\CardWrapper;
use ApiBank\Wrappers\ClientWrapper;
use ApiBank\Wrappers\ProductWrapper;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ApiBank
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * ApiBank constructor.
     *
     * @param string $apiUrl
     * @param bool $verify
     * @param AccessToken $accessToken
     */
    public function __construct(string $apiUrl, bool $verify, AccessToken $accessToken)
    {
        $this->client = new Client(['base_uri' => $apiUrl, 'verify' => $verify]);
        $this->accessToken = $accessToken;
    }

    public function client(): ClientWrapper
    {
        return new ClientWrapper($this->accessToken, $this->client);
    }

    public function card(): CardWrapper
    {
        return new CardWrapper($this->accessToken, $this->client);
    }

    public function products(): ProductWrapper
    {
        return new ProductWrapper($this->accessToken, $this->client);
    }
}
