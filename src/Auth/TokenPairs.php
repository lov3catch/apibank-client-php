<?php

declare(strict_types=1);

namespace ApiBank\Auth;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\Auth\Tokens\RefreshToken;

class TokenPairs
{
    /**
     * @var AccessToken
     */
    private $accessToken;
    /**
     * @var RefreshToken
     */
    private $refreshToken;

    /**
     * TokenPairs constructor.
     *
     * @param AccessToken $accessToken
     * @param RefreshToken $refreshToken
     */
    public function __construct(AccessToken $accessToken, RefreshToken $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return RefreshToken
     */
    public function getRefreshToken(): RefreshToken
    {
        return $this->refreshToken;
    }
}