<?php

declare(strict_types=1);

namespace ApiBank\Auth\Tokens;

final class AccessToken extends AbstractToken
{
    private const
        TOKEN_KEY = 'access_token',
        LIFETIME_KEY = 'expires_in';

    public function __construct(string $token, ?int $lifetime)
    {
        parent::__construct($token, $lifetime);
    }

    /**
     * @param array $json
     *
     * @return AccessToken
     */
    public static function createFromJson(array $json): self
    {
        return new self($json[self::TOKEN_KEY], $json[self::LIFETIME_KEY] ?? null);
    }

    public function asBearer(): string
    {
        return 'Bearer ' . $this->getToken();
    }
}