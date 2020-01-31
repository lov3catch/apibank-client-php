<?php

declare(strict_types=1);

use ApiBank\Auth\AuthManager;
use ApiBank\Auth\TokenPairs;
use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\Auth\Tokens\RefreshToken;
use PHPUnit\Framework\TestCase;

class AuthManagerTest extends TestCase
{
    /**
     * @var AuthManager
     */
    private $authManager;

    protected function setUp(): void
    {
        $this->authManager = new AuthManager(
            getenv('APP_ID'),
            getenv('APP_SECRET'),
            getenv('LOGIN'),
            getenv('PASSWORD'),
            getenv('GENERATE_TOKEN_URL'),
            getenv('REFRESH_TOKEN_URL'),
            (bool)getenv('VERIFY_SSL'));
    }

    public function testAuthManager()
    {
        $this->assertInstanceOf(AuthManager::class, $this->authManager);
    }

    public function testGenerateTokens()
    {
        $tokenPairs = $this->authManager->generate();

        $this->assertInstanceOf(TokenPairs::class, $tokenPairs);

        $this->assertInstanceOf(AccessToken::class, $tokenPairs->getAccessToken());
        $this->assertInstanceOf(RefreshToken::class, $tokenPairs->getRefreshToken());

        $this->assertIsString($tokenPairs->getAccessToken()->getToken());
        $this->assertIsString($tokenPairs->getRefreshToken()->getToken());

        $this->assertIsInt($tokenPairs->getAccessToken()->getLifetime());
        $this->assertIsInt($tokenPairs->getRefreshToken()->getLifetime());
    }

    /**
     * @depends testGenerateTokens
     */
    public function testRefreshTokens()
    {
        $tokenPairs = $this->authManager->refresh($this->authManager->generate()->getRefreshToken());

        $this->assertInstanceOf(AccessToken::class, $tokenPairs->getAccessToken());
        $this->assertInstanceOf(RefreshToken::class, $tokenPairs->getRefreshToken());

        $this->assertIsString($tokenPairs->getAccessToken()->getToken());
        $this->assertIsString($tokenPairs->getRefreshToken()->getToken());

        $this->assertIsInt($tokenPairs->getAccessToken()->getLifetime());
        $this->assertIsInt($tokenPairs->getRefreshToken()->getLifetime());
    }
}
