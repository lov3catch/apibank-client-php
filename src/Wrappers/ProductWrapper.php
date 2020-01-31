<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Product;
use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use function Formapro\Values\set_values;

class ProductWrapper
{
    use ValuesTrait;
    use ObjectsTrait;

    /**
     * @var Client
     */
    private $client;
    /**
     * @var AccessToken
     */
    private $accessToken;

    public function __construct(AccessToken $accessToken, ClientInterface $client)
    {
        $this->accessToken = $accessToken;
        $this->client = $client;
    }

    /**
     * @param int $bankClientId
     * @return \Generator
     */
    public function read(int $bankClientId): \Generator
    {
        $headers = ['Authorization' => $this->accessToken->asBearer()];

        $options = [
            'headers' => $headers,
        ];

        $response = $this->client->get('clients/' . $bankClientId . '/products', $options);

        // todo: throw exception
        if (200 !== $response->getStatusCode()) return new Response($response->getStatusCode());

        $productsInfo = json_decode($response->getBody()->read($response->getBody()->getSize()), true);

        foreach ($productsInfo as $productInfo) {
            $product = new Product();
            set_values($product, $productInfo);

            yield $product;
        }
    }
}