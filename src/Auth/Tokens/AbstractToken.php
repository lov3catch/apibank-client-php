<?php

declare(strict_types=1);

namespace ApiBank\Auth\Tokens;

abstract class AbstractToken implements TokenInterface
{
    protected $token;
    protected $lifetime;

    public function __construct(string $token, ?int $lifetime)
    {
        $this->token = $token;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * {@inheritDoc}
     */
    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }
}