<?php

namespace Fruitsbytes\PHP\Moncash;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;
use Dotenv\Dotenv;

class Moncash
{
    private string $mode;
    private string $clientSecret;
    private string $clientId;
    private array $credentials;
    private bool $isProduction;
    private string $endpoint;
    private string $restApi;
    private string $gatewayBase;
    private Client $client;
    private string $accessToken;

    /**
     * @param  string  $clientId  The clientID
     * @param  string  $clientSecret
     * @param  string  $mode
     * @param  float  $timeout
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $mode = 'sandbox',
        float $timeout = 2.0
    ) {

        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->clientId     = $clientId ?? $_ENV['CLIENT_ID'];
        $this->clientSecret = $clientSecret ?? $_ENV['CLIENT_ID'];
        $this->mode         = $mode ?? $_ENV['CLIENT_ID'];

        $this->isProduction = $mode === 'sandbox';

        $this->credentials = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'mode'          => $this->mode,
        ];

        $this->isProduction = $this->credentials['mode'] === 'sandbox';

        if ($this->isProduction) {
            $this->endpoint = 'https://moncashbutton.digicelgroup.com';
        } else {
            $this->endpoint = 'https://sandbox.moncashbutton.digicelgroup.com';
        }

        $this->restApi     = '/Api';
        $this->gatewayBase = '/Moncash-middleware';

        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => $timeout,
            'headers'  => ['Accept' => 'application/json']
        ]);
    }

    /**
     * To call the resources of the Rest API MonCash, you must authenticate by including the bearer token in the
     * Authorization header with the Bearer authentication scheme. The value is Bearer <Access-Token> or Basic
     * <client_id>:<secret>. The client_id and client_secret can be generated from the business portal.
     * @throws GuzzleException
     */
    public function getAccessToken(): string|null
    {

        $url = "$this->restApi/oauth/token";

        $response = $this->client->post($url, [
            'auth'  => [
                $this->credentials['client_id'],
                $this->credentials['client_secret']
            ],
            'query' => [
                'grant_type' => 'client_credentials',
                'scope'      => 'read,write'
            ]
        ]);

        $response = $response->getBody();

        $this->accessToken = $response['access_token'] ?? null;

        return $this->accessToken;
    }

    /**
     * Check if token already exist if not get it
     * @return void
     * @throws MonCashException
     */
    private function touchToken()
    {
        // TODO verify expiration
        if ( ! $this->accessToken) {
            try {
                $this->getAccessToken();
            } catch (Throwable $e) {
                throw new MonCashException($e->getMessage());
            }

        }
    }


    /**
     * To create a payment you must send the orderId and amount as HTTP POST with an
     * Authorization Bearer Token. The response will be a JSON (or other format) with the
     * parameters success and redirect URL to load the Payment Gateway of MonCash
     * Middleware.
     *
     * @param  float  $amount
     * @param  string  $orderId
     *
     * @return PaymentRequestResult The redirection URL + token
     * @throws MonCashException
     */
    public function createPayment(float $amount, string $orderId)
    {

        if ($amount <= 10) {
            throw new MonCashException('amount should bye greater than 10');
        }

        $this->touchToken();

        try {
            $payment = $this->client->post("$this->restApi/v1/CreatePayment", [
                [
                    'auth' =>
                        ['Bearer', $this->accessToken],
                    'json' => [
                        'amount'  => $amount,
                        'orderId' => $orderId
                    ]
                ]
            ]);

            $token = $payment['payment_token']['token'];

            return new PaymentRequestResult(
                "$this->endpoint.$this->gatewayBase/Payment/Redirect?token=".$token,
                $token.""
            );
        } catch (Throwable $e) {
            throw new MonCashException($e->getMessage());
        }

    }

    /**
     * To get a payment details from the return URL business script you must send the transactionId and orderId as
     * HTTP POST with an Authorization Bearer Token. The response will be a JSON (or other format) with the
     * payment details.
     *
     * @param  string  $id  Transaction ID
     *
     * @return TransfertRequestResult
     * @throws MonCashException
     */
    public function getPaymentByTransaction(string $id)
    {

        $this->touchToken();

        try {
            /**
             * @var $paymentResult TransfertRequestResult
             */
            $paymentResult = $this->client->post("$this->restApi/v1/RetrieveTransactionPayment", [
                [
                    'auth' =>
                        ['Bearer', $this->accessToken],
                    'json' => [
                        'transactionId' => $id
                    ]
                ]
            ])->getBody();

            if ($paymentResult['status'] == 200) {
                return $paymentResult;
            } else {
                throw new MonCashException('Error');
            }
        } catch (Throwable $e) {
            throw new MonCashException($e->getMessage());
        }
    }

    /**
     * To get a payment details from the return URL business script you must send the transactionId and orderId as
     * HTTP POST with an Authorization Bearer Token. The response will be a JSON (or other format) with the
     * payment details.
     *
     * @param  string  $id
     *
     * @return TransfertRequestResult
     * @throws MonCashException
     */
    public function getPaymentByOrder(string $id)
    {

        $this->touchToken();

        try {
            /**
             * @var $paymentResult TransfertRequestResult
             */
            $paymentResult = $this->client->post("$this->restApi/v1/RetrieveOrderPayment", [
                [
                    'auth' =>
                        ['Bearer', $this->accessToken],
                    'json' => [
                        'transactionId' => $id
                    ]
                ]
            ])->getBody();

            if ($paymentResult['status'] == 200) {
                return $paymentResult;
            } else {
                throw new MonCashException('Error');
            }
        } catch (Throwable $e) {
            throw new MonCashException($e->getMessage());
        }
    }

    /**
     * @param  int  $amount
     * @param  string  $receiver
     * @param  string  $description
     *
     * @return TransfertRequestResult
     * @throws MonCashException
     */
    public function transfer(int $amount, string $receiver, string $description = '')
    {
        $this->touchToken();

        try {
            /**
             * @var $paymentResult TransfertRequestResult
             */
            $paymentResult = $this->client->post("$this->restApi/v1/Transfert", [
                [
                    'auth' =>
                        ['Bearer', $this->accessToken],
                    'json' => [
                        'amount'   => $amount,
                        'receiver' => $receiver,
                        'desc'     => $description
                    ]
                ]
            ])->getBody();

            if ($paymentResult['status'] == 200) {
                return $paymentResult;
            } else {
                throw new MonCashException('Error');
            }
        } catch (Throwable $e) {
            throw new MonCashException($e->getMessage());
        }
    }

}
