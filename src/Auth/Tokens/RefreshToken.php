<?php

declare(strict_types=1);

namespace ApiBank\Auth\Tokens;

final class RefreshToken extends AbstractToken
{
    private const
        TOKEN_KEY = 'refresh_token',
        LIFETIME_KEY = 'refresh_expires_in';

    public function __construct(string $token, ?int $lifetime)
    {
        parent::__construct($token, $lifetime);
    }

    /**
     * @param array $json
     *
     * @return RefreshToken
     */
    public static function createFromJson(array $json): self
    {
        return new self($json[self::TOKEN_KEY], $json[self::LIFETIME_KEY] ?? null);
    }
}