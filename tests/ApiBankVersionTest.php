<?php

declare(strict_types=1);

use ApiBank\ApiBank;
use ApiBank\Auth\Tokens\AccessToken;
use PHPUnit\Framework\TestCase;

class ApiBankVersionTest extends TestCase
{
    public function testVersion()
    {
        $version =
            (new ApiBank(getenv('API_URL'),
                (bool)getenv('VERIFY_SSL'),
                new AccessToken('', 1)))->version();

        $this->assertEquals('3.2.0', $version->apiVersion);
        $this->assertEquals('0.0.6', $version->libVersion);
    }
}
