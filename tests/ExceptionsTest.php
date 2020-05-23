<?php

declare(strict_types=1);

use ApiBank\Exceptions\ApiBankException;
use ApiBank\Exceptions\DuplicateClientException;
use ApiBank\Exceptions\OperationNotFoundException;
use ApiBank\Exceptions\UnauthorizedException;
use ApiBank\Exceptions\UpgradeClientException;
use ApiBank\Factories\ExceptionFactory;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExceptionsTest extends TestCase
{
    /**
     * @var ExceptionFactory
     */
    private $exceptionFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exceptionFactory = new ExceptionFactory();
    }

    /**
     * @param Throwable $throwable
     * @dataProvider unauthorizedExceptionProvider
     */
    public function testShouldBeUnauthorizedException(Throwable $throwable)
    {
        $convertedException = $this->exceptionFactory->from($throwable);

        $this->assertInstanceOf(ApiBankException::class, $convertedException);
        $this->assertInstanceOf(UnauthorizedException::class, $convertedException);
        $this->assertInstanceOf(Response::class, $convertedException->getResponse());
        $this->assertEquals(401, $convertedException->getResponse()->getStatusCode());
    }

    /**
     * @param Throwable $throwable
     * @dataProvider internalExceptionProvider
     */
    public function testShouldBeDefaultException(Throwable $throwable)
    {
        $convertedException = $this->exceptionFactory->from($throwable);

        $this->assertInstanceOf(Exception::class, $convertedException);
    }

    /**
     * @param Throwable $throwable
     * @dataProvider apiBankExceptionProvider
     */
    public function testShouldBeNamedException(Throwable $throwable)
    {
        $convertedException = $this->exceptionFactory->from($throwable);

        $this->assertInstanceOf(ApiBankException::class, $convertedException);

        if ($convertedException instanceof DuplicateClientException) {
            $this->assertInstanceOf(Response::class, $convertedException->getResponse());
            $this->assertEquals(400, $convertedException->getResponse()->getStatusCode());
        }

        if ($convertedException instanceof UpgradeClientException) {
            $this->assertInstanceOf(Response::class, $convertedException->getResponse());
            $this->assertEquals(400, $convertedException->getResponse()->getStatusCode());
        }

        if ($convertedException instanceof OperationNotFoundException) {
            $this->assertInstanceOf(Response::class, $convertedException->getResponse());
            $this->assertEquals(404, $convertedException->getResponse()->getStatusCode());
        }
    }

    public function internalExceptionProvider()
    {
        yield [new Exception()];
    }

    public function unauthorizedExceptionProvider()
    {
        yield [new ClientException(
            'TestException',
            new Request('GET', 'http://example.com'),
            new Response(401))];
    }

    public function apiBankExceptionProvider()
    {
        yield [new ClientException(
            'TestException',
            new Request('POST', 'http://example.com'),
            new Response(400, [], json_encode([
                'errors' => [
                    ['code' => 'CE001', 'message' => 'Error message']
                ]
            ])))];

        yield [new ClientException(
            'TestException',
            new Request('PATCH', 'http://example.com'),
            new Response(400, [], json_encode([
                'errors' => [
                    ['code' => 'CE002', 'message' => 'Error message']
                ]
            ])))];

        yield [new ClientException(
            'TestException',
            new Request('GET', 'http://example.com'),
            new Response(404, [], json_encode([
                'errors' => [
                    ['code' => 'F003', 'message' => 'Error message']
                ]
            ])))];
    }
}
