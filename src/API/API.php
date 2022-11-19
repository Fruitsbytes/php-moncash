<?php

namespace Fruitsbytes\PHP\MonCash\API;


use Fruitsbytes\PHP\MonCash\Configuration\Configuration;
use Fruitsbytes\PHP\MonCash\Configuration\ConfigurationException;
use Fruitsbytes\PHP\MonCash\Strategy\TokenMachine\TokenMachineException;


class API
{
    protected Configuration $configuration;

    /**
     * @throws ConfigurationException
     */
    public function __construct(?Configuration $configuration)
    {
        // TODO in PHP 8.1 initialize during promotion and dependency injection to cast array into Class
        $this->configuration = new Configuration($configuration);

    }

    /**
     * @throws APIException
     */
    public function getToken(): string
    {
        return $this->configuration->tokenMachine->getToken() ?? $this->getFreshToken();
    }


    /**
     * @throws APIException
     */
    private function getFreshToken(): string
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->configuration->restApi,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->configuration->clientId.":".$this->configuration->clientSecret,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => 'scope=read%2Cwrite&grant_type=client_credentials',
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


        $response = curl_exec($curl);

        curl_close($curl);

        json_decode($response, true);
        if ($httpCode != 200) {
            throw new APIException(
                'Error Getting new Token:'.(
                    $response['error'] ??
                    $response['message'] ??
                    ''
                )
            );
        }

        return $response['access_token'];
    }


    /**
     * @throws APIException
     */
    protected function post(string $endpoint, array $data): bool|string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->configuration->restApi.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '.$this->getToken()
            ),
        ));


        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = curl_exec($curl);
        curl_close($curl);


        json_decode($response, true);
        if ($httpCode != 200) {
            throw new APIException($response['error'] ?? $response['message'] ?? '');
        }

        return $response;
    }

}
