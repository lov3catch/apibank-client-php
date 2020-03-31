<?php

declare(strict_types=1);

namespace ApiBank\Factories;

use ApiBank\Exceptions\DuplicateClientException;
use ApiBank\Exceptions\OperationNotFoundException;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Exceptions\UpgradeClientException;
use Psr\Http\Message\ResponseInterface;

class ExceptionFactory
{
    private const DEFAULT_EXCEPTION_MESSAGE = 'ApiBank. Has no error code and error message.';

    public function fromResponse(ResponseInterface $response): \Throwable
    {
        if (401 === $response->getStatusCode()) return new UnauthorizedException();

        $responseData = json_decode($response->getBody()->getContents(), true);

        if (!isset($responseData['errors'][0]['code']) || !isset($responseData['errors'][0]['message'])) return new \Exception(self::DEFAULT_EXCEPTION_MESSAGE);

        /**
         * @var $exception \Exception
         */
        foreach ([
                     new DuplicateClientException(),
                     new UpgradeClientException(),
                     new OperationNotFoundException(),
                 ] as $exception) {
            if ($exception->getCode() === $responseData['errors'][0]['code']) return $exception;
        }

        return new \Exception(self::DEFAULT_EXCEPTION_MESSAGE);
    }
}