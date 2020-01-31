<?php

declare(strict_types=1);

namespace ApiBank\Auth\Tokens;

interface TokenInterface
{
    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return int|null
     */
    public function getLifetime(): ?int;
}