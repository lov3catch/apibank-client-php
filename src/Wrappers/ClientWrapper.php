<?php

declare(strict_types=1);

namespace ApiBank\Wrappers;

use ApiBank\Auth\Tokens\AccessToken;
use ApiBank\DTObjects\Client as BankClient;
use ApiBank\DTObjects\OperationStatus;
use ApiBank\DTValues\BirthCity;
use ApiBank\DTValues\BirthCountry;
use ApiBank\DTValues\Birthdate;
use ApiBank\DTValues\Name;
use ApiBank\DTValues\Passport;
use ApiBank\DTValues\PassportDate;
use ApiBank\DTValues\PassportDiv;
use ApiBank\DTValues\PassportInfo;
use ApiBank\DTValues\Patronymic;
use ApiBank\DTValues\Phone;
use ApiBank\DTValues\PostAddress;
use ApiBank\DTValues\RealAddress;
use ApiBank\DTValues\RegAddress;
use ApiBank\DTValues\Snils;
use ApiBank\DTValues\Surname;
use ApiBank\DTValues\Url;
use ApiBank\Factories\ExceptionFactory;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Throwable;
use function Formapro\Values\set_values;

class ClientWrapper
{
    private const BANK_CLIENT_ID_KEY = 'client_id';

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

    public function create(Phone $phone): BankClient
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
            'Content-Type'  => 'application/json',
        ];

        $data = [
            'bank_code' => 'AKBARS',
            'phone'     => $phone->value(),
        ];

        $options = [
            'body'    => json_encode($data),
            'headers' => $headers,
        ];

        try {
            $response = $this->client->post('clients/anonymous', $options);

            $bankClientId = json_decode($response->getBody()->getContents(), true)[self::BANK_CLIENT_ID_KEY];

            return $this->read($bankClientId);
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }

    public function read(int $bankClientId): BankClient
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
        ];

        $options = [
            'headers' => $headers,
        ];

        try {
            $response = $this->client->get('clients/' . $bankClientId, $options);

            $clientInfo = json_decode($response->getBody()->getContents(), true);

            $bankClient = new BankClient();
            set_values($bankClient, $clientInfo);

            return $bankClient;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }

    /**
     * @param string $randomUniqueString
     * @param int $bankClientId
     * @param Url $postBackUrl
     * @param Surname $surname
     * @param Name $name
     * @param Patronymic $patronymic
     * @param Birthdate $birthDate
     * @param BirthCountry $birthCountry
     * @param BirthCity $birthCity
     * @param Passport $passport
     * @param PassportDate $passportDate
     * @param PassportDiv $passportDiv
     * @param PassportInfo $passportInfo
     * @param RealAddress $realAddress
     * @param RegAddress $regAddress
     * @param PostAddress $postAddress
     * @param Snils $snils
     * @param bool $isRussianCitizenship
     * @param bool $isRussianTaxResidency
     * @param bool $isForeignCitizenship
     * @return OperationStatus
     * @throws Throwable todo: check tests
     */
    public function upgrade(
        string $randomUniqueString,
        int $bankClientId,
        Url $postBackUrl,
        Surname $surname,
        Name $name,
        Patronymic $patronymic,
        Birthdate $birthDate,
        BirthCountry $birthCountry,
        BirthCity $birthCity,
        Passport $passport,
        PassportDate $passportDate,
        PassportDiv $passportDiv,
        PassportInfo $passportInfo,
        RealAddress $realAddress,
        RegAddress $regAddress,
        PostAddress $postAddress,
        Snils $snils,
        bool $isRussianCitizenship,
        bool $isRussianTaxResidency,
        bool $isForeignCitizenship
    ): OperationStatus
    {
        $headers = [
            'Authorization' => $this->accessToken->asBearer(),
            'Content-Type'  => 'application/json',
        ];

        $body = [
            'operation_id'             => $randomUniqueString,
            'url_status'               => $postBackUrl->value(),
            'surname'                  => $surname->value(),
            'name'                     => $name->value(),
            'patronymic'               => $patronymic->value(),
            'birthdate'                => $birthDate->value(),
            'birth_country'            => $birthCountry->value(),
            'birth_city'               => $birthCity->value(),
            'passport'                 => $passport->value(),
            'passport_date'            => $passportDate->value(),
            'passport_div'             => $passportDiv->value(),
            'passport_info'            => $passportInfo->value(),
            'real_address'             => $realAddress->value(),
            'reg_address'              => $regAddress->value(),
            'post_address'             => $postAddress->value(),
            'snils'                    => $snils->value(),
            'bank_code'                => 'AKBARS',
            'questions'                => [
                'russian_citizenship'   => $isRussianCitizenship,
                'foreign_citizenship'   => $isForeignCitizenship,
                'russian_tax_residency' => $isRussianTaxResidency,
            ],
        ];

        $options = [
            'body'    => json_encode($body),
            'headers' => $headers,
        ];

        try {
            $response = $this->client->post('clients/' . $bankClientId . '/upgrade-account-level-to-uprid', $options);

            $upgradeAccountLevelInfo = json_decode($response->getBody()->getContents(), true);

            $upgradeAccountLevel = new OperationStatus();
            set_values($upgradeAccountLevel, $upgradeAccountLevelInfo);

            return $upgradeAccountLevel;
        } catch (ClientException $exception) {
            throw (new ExceptionFactory())->fromResponse($exception->getResponse());
        }
    }
}