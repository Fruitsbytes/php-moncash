<?php

namespace Fruitsbytes\PHP\MonCash\API;


use Exception;
use Fruitsbytes\PHP\MonCash\Configuration\Configuration;
use Fruitsbytes\PHP\MonCash\Configuration\ConfigurationException;
use http\Params;


class API
{
    protected Configuration $configuration;

    /**
     * @throws ConfigurationException
     */
    public function __construct(Configuration|array $configuration = [])
    {
        // TODO in PHP 8.1 initialize during promotion and dependency injection to cast array into Class
        $this->configuration = new Configuration($configuration);

    }

    /**
     * @throws APIException
     */
    public function getToken(): string
    {
        $oldToken = '';
        try {
            $oldToken = $this->configuration->tokenMachine->getToken();
        } catch (Exception) {
        }

        return ! empty($oldToken) ? $oldToken : $this->getFreshToken();
    }


    /**
     * @throws APIException
     */
    private function getFreshToken(): string
    {

        $curl = curl_init($this->configuration->restApi.'/oauth/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $this->configuration->clientId.":".$this->configuration->clientSecret);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'scope=read%2Cwrite&grant_type=client_credentials',);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
        ]);


        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        curl_close($curl);

        if ($response === false) {
            throw new APIException('Invalid POST request 0');
        }
        $response = json_decode($response, true);

        if ((int)$httpCode >= 300 || (int)$httpCode < 200) {
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
    protected function post(string $endpoint, array $data): array
    {

        $token = $this->getToken();

        $curl = curl_init($this->configuration->restApi.$endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        curl_close($curl);

        $response = json_decode($response, true);

        if ((int)$httpCode >= 300 || (int)$httpCode < 200) {
            throw new APIException($response['error'] ?? $response['message'] ?? 'error'.$httpCode);
        }

        return $response;
    }

}
