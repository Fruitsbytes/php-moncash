<?php

namespace Fruitsbytes\PHP\Moncash;

use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\DefaultSecretManager;
use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\SecretManagerException;
use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\SecretManagerInterface as SecretManager;
use Fruitsbytes\PHP\Moncash\Strategy\TokenMachine\TokenMachineInterface as TojenMachine;

abstract class Configuration
{
    /**
     * @var string The mode of the current instance. enum: 'sandbox'|'production' the default value is 'sandbox'.
     */
    public string $mode;
    /**
     * @var string|null
     */
    public string|null $clientSecret;
    /**
     * @var string|null
     */
    public string|null $clientId;
    /**
     * @var string|null
     */
    public string|null $businessKey;
    /**
     * @var string
     */
    public string $endpoint;
    /**
     * @var string
     */
    public string $restApi;
    /**
     * @var string
     */
    public string $gatewayBase;
    /**
     * @var float Number of seconds it waits for the server to respond.
     */
    public float $timeout;

    /**
     * @var  SecretManager
     */
    public SecretManager $secretManager;

    /**
     * @var TojenMachine
     */
    public TojenMachine $tokenMachine;

    /**
     * @param  array{
     *               mode: string,
     *               clientSecret: string,
     *               clientId: string,
     *               businessKey: string,
     *               endpoint: string,
     *               restApi: string,
     *               gatewayBase: string,
     *               timeout: float,
     *               secretManager: string|SecretManager,
     *               tokenMachine: string|TojenMachine,
     *          }|null  $config  You specify the configuration to override for this instance
     *
     * @throws SecretManagerException
     */
    public function __construct(?array $config)
    {
        $this->clientId     = $config['clientId'] ?? getenv('MONCASH_CLIENT_ID');
        $this->clientSecret = $config['clientSecret'] ?? getenv('MONCASH_CLIENT_SECRET');
        $this->mode         = $config['businessKey'] ?? getenv('MONCASH_BUSINESS_KEY') ?? 'sandbox';
        $this->businessKey  = $config['timeout'] ?? 60;

        $this->timeout = $config['timeout'] ?? 60;

        $this->setSecretManager($config['secretManager']);

    }

    /**
     * @param  string|SecretManager  $manager
     *
     * @return void
     * @throws SecretManagerException
     */
    private function setSecretManager(string|SecretManager $manager): void
    {
        if (is_string($manager)) {

            $secretManagerNamespaced = '\\Fruitsbytes\\PHP\\Moncash\\Strategy\\SecretManager\\'.$manager;

            if (
                class_exists($manager) &&
                is_subclass_of($manager, SecretManager::class, true)
            ) {
                $secretManagerClass = $manager;
            } elseif (
                class_exists($secretManagerNamespaced) &&
                is_subclass_of($secretManagerNamespaced, SecretManager::class, true)
            ) {
                $secretManagerClass = $secretManagerNamespaced;
            } else {
                throw new SecretManagerException('INVALID_SECRET_MANAGER');
            }

            $this->secretManager = new $secretManagerClass();
        } elseif (is_subclass_of($manager, SecretManager::class)) {
            $this->secretManager = new $manager();
        } elseif ( ! isset($manager)) {
            $this->secretManager = new DefaultSecretManager();
        } else {
            throw new SecretManagerException('INVALID_SECRET_MANAGER');
        }
    }

    public function isProduction()
    {
        return $this->mode === 'production';
    }
}
