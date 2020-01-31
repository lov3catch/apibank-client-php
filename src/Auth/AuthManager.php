<?php

declare(strict_types=1);

namespace ApiBank\Auth;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\Auth\Tokens\RefreshToken;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class AuthManager
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $generateTokenUrl;

    /**
     * @var string
     */
    private $refreshTokenUrl;

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(string $appId, string $appSecret, string $login, string $password, string $generateTokenUrl, string $refreshTokenUrl, bool $verify)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->login = $login;
        $this->password = $password;
        $this->generateTokenUrl = $generateTokenUrl;
        $this->refreshTokenUrl = $refreshTokenUrl;
        $this->client = new Client(['verify' => $verify]);
    }

    public function generate(): TokenPairs
    {
        return $this->doRequest($this->generateTokenUrl, $this->buildCreateTokenOptions());
    }

    public function refresh(RefreshToken $refreshToken): TokenPairs
    {
        return $this->doRequest($this->generateTokenUrl, $this->buildRefreshTokenOptions($refreshToken));
    }

    private function doRequest(string $uri, array $options): TokenPairs
    {
        try {
            /** @var ResponseInterface $response */
            $response = $this->client->post($uri, $options);

            $content = json_decode($response->getBody()->getContents(), true);

            return new TokenPairs(
                AccessToken::createFromJson($content),
                RefreshToken::createFromJson($content));
        } catch (ClientException $exception) {
            // todo: check exception?
        }
    }

    /**
     * @return array
     */
    private function buildCreateTokenOptions(): array
    {
        $options = [];
        $options['auth'] = [$this->appId, $this->appSecret];
        $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $options['form_params']['username'] = $this->login;
        $options['form_params']['password'] = $this->password;
        $options['form_params']['grant_type'] = 'password';

        return $options;
    }

    private function buildRefreshTokenOptions(RefreshToken $refreshToken): array
    {
        $options = [];
        $options['auth'] = [$this->appId, $this->appSecret];
        $options['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $options['form_params']['username'] = $this->login;
        $options['form_params']['password'] = $this->password;
        $options['form_params']['grant_type'] = 'refresh_token';
        $options['form_params']['refresh_token'] = $refreshToken->getToken();

        return $options;
    }
}