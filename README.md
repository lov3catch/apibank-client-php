
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e2826d9c7669441fa57ac7ab6fa0f231)](https://app.codacy.com/manual/lov3catch/apibank-client-php?utm_source=github.com&utm_medium=referral&utm_content=lov3catch/apibank-client-php&utm_campaign=Badge_Grade_Dashboard)

**PHP wrapper for [APIBANK](https://apibank.club)**


#### Get token pairs

```php
$toketPairs = (new AuthManager(...$args))->generate();

$accessToken = $tokenPairs->getAccessToken();

$refreshToken = $tokenPairs->getRefreshToken();
```

#### Create apibank client

```php
$apiBankClient = new ApiBank('http://api-url-example.com', true, $accessToken);
```

#### Send requests

- Transfer funds from partner to client
```php
$cardWrapper = $apiBankClient->card();

$ean = '4bcb8f6b17d041cf8bad0337d84e25df';
$amountToTransfer = 15.5;

$isSuccess = $cardWrapper->transferFromPartnerToClient($ean, $amountToTransfer); // bool
```


PS: feel free to make pullrequest

