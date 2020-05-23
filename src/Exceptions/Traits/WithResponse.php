<?php

declare(strict_types=1);

namespace ApiBank\Exceptions\Traits;

use Psr\Http\Message\ResponseInterface;

trait WithResponse
{
    protected $response = null;

    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}