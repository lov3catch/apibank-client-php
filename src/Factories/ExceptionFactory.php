<?php

declare(strict_types=1);

namespace ApiBank\Factories;

use ApiBank\Exceptions\DefaultException;
use ApiBank\Exceptions\DuplicateClientException;
use ApiBank\Exceptions\OperationNotFoundException;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Exceptions\UpgradeClientException;
use GuzzleHttp\Exception\ClientException;

class ExceptionFactory
{
    public function from(\Throwable $throwable): \Throwable
    {
        if ($throwable instanceof ClientException) {
            $resp = $throwable->getResponse();

            if (401 === $resp->getStatusCode()) return (new UnauthorizedException())->setResponse($resp);

            $respContents = json_decode($resp->getBody()->getContents(), true);

            if (!isset($respContents['errors'][0]['code']) || !isset($respContents['errors'][0]['message'])) return (new DefaultException())->setResponse($resp);

            $exceptions = [
                (new DuplicateClientException())->setResponse($resp),
                (new UpgradeClientException())->setResponse($resp),
                (new OperationNotFoundException())->setResponse($resp),
            ];

            /**
             * @var $exception \Exception
             */
            foreach ($exceptions as $exception) {
                if ($exception->getCode() === $respContents['errors'][0]['code']) return $exception;
            }
        }

        return new \Exception();
    }
}