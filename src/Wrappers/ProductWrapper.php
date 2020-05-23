<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Product;
use ApiBank\Factories\ExceptionFactory;
use Formapro\Values\ObjectsTrait;
use Formapro\Values\ValuesTrait;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
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

    public function read(int $bankClientId): Product
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $response = $this->client->get('clients/' . $bankClientId . '/products', $options);

            // todo: return first now. In future can be array. But now - return first.
            foreach (json_decode($response->getBody()->getContents(), true) as $productInfo) {
                $product = new Product();
                set_values($product, $productInfo);
                return $product;
            }
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->from($exception);
        }
    }
}