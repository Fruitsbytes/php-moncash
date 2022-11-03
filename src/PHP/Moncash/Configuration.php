<?php

namespace Fruitsbytes\PHP\Moncash;

use ArrayAccess;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Fruitsbytes\PHP\Moncash\Strategy\PhoneValidation\LibPhoneValidation;
use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\DefaultSecretManager;
use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\SecretManagerException;
use Fruitsbytes\PHP\Moncash\Strategy\SecretManager\SecretManagerInterface as SecretManager;
use Fruitsbytes\PHP\Moncash\Strategy\TokenMachine\FileTokenMachine;
use Fruitsbytes\PHP\Moncash\Strategy\TokenMachine\TokenMachineInterface as TokenMachine;
use Fruitsbytes\PHP\Moncash\Strategy\IdempotenceKeyMaker\IdempotenceKeyMakerInterface as IdempotenceKeyMaker;
use Fruitsbytes\PHP\Moncash\Strategy\PhoneValidation\PhoneValidationInterface as PhoneValidation;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use ReflectionException;
use ReflectionProperty;


/**
 * Configuration helper fpr this library
 */
class Configuration implements ArrayAccess
{

    /**
     * List of accepted attributes for the array
     */
    const VARS = [
        'mode', 'clientSecret', 'clientId', 'businessKey', 'endpoint', 'restApi', 'gatewayBase', 'timeout',
        'secretManager', 'tokenMachine', 'phoneValidation', 'idempotenceKeyMaker'
    ];

    /**
     * The shape of the array
     */
    const CONFIGURATION_SHAPE = [
        'mode'                => 'string',
        'lang'                => 'string',
        'clientSecret'        => 'string',
        'clientId'            => 'string',
        'businessKey'         => 'string',
        'endpoint'            => 'string',
        'restApi'             => 'string',
        'gatewayBase'         => 'string',
        'timeout'             => 'string',
        'secretManager'       => SecretManager::class,
        'tokenMachine'        => TokenMachine::class,
        'phoneValidation'     => PhoneValidation::class,
        'idempotenceKeyMaker' => IdempotenceKeyMaker::class,
    ];

    const DEFAULT_CONFIG =[
        'mode'                => 'sandbox',
        'lang'                => 'en',
        'clientSecret'        => '',
        'clientId'            => '',
        'businessKey'         => '',
        'timeout'             => 60,
        'secretManager'       =>  DefaultSecretManager::class,
        'tokenMachine'        =>  FileTokenMachine::class,
        'phoneValidation'     =>  LibPhoneValidation::class,
        'idempotenceKeyMaker' =>  ,
    ]


    /**
     * @var string The mode of the current instance. enum: 'sandbox'|'production' the default value is 'sandbox'.
     */
    #[ExpectedValues(['sandbox', 'production'])]
    public string $mode;

    /**
     * @var string|null
     */
    #[ExpectedValues(['en', 'fr', 'ht'])]
    public ?string $lang;

    /**
     * @var string|null
     */
    #[\SensitiveParameter] // PHP 8.2
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
     * @var TokenMachine
     */
    public TokenMachine $tokenMachine;

    /**
     * @var PhoneValidation
     */
    public PhoneValidation $phoneValidation;

    /**
     * @var IdempotenceKeyMaker
     */
    public IdempotenceKeyMaker $idempotenceKeyMaker;

    /**
     * @var  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string,'lang': string,
     *     'businessKey': string, 'endpoint': string, 'restApi': string, 'gatewayBase': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'idempotenceKeyMaker': IdempotenceKeyMaker
     * }
     */
    #[ArrayShape([
        'mode'                => 'string',
        'lang'                => 'string',
        'clientSecret'        => 'string',
        'clientId'            => 'string',
        'businessKey'         => 'string',
        'endpoint'            => 'string',
        'restApi'             => 'string',
        'gatewayBase'         => 'string',
        'timeout'             => 'string',
        'secretManager'       => SecretManager::class,
        'tokenMachine'        => TokenMachine::class,
        'phoneValidation'     => PhoneValidation::class,
        'idempotenceKeyMaker' => IdempotenceKeyMaker::class,
    ])]
    private array $array = [];

    /**
     * @var  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'endpoint': string, 'restApi': string, 'gatewayBase': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'idempotenceKeyMaker': IdempotenceKeyMaker
     * }
     */
    #[ArrayShape([
        'mode'                => 'string',
        'lang'                => 'string',
        'clientSecret'        => 'string',
        'clientId'            => 'string',
        'businessKey'         => 'string',
        'endpoint'            => 'string',
        'restApi'             => 'string',
        'gatewayBase'         => 'string',
        'timeout'             => 'string',
        'secretManager'       => SecretManager::class,
        'tokenMachine'        => TokenMachine::class,
        'phoneValidation'     => PhoneValidation::class,
        'idempotenceKeyMaker' => IdempotenceKeyMaker::class,
    ])]
    private array $serverConfig = [];

    /**
     * Configuration instance for the **Fruitsbytes\PHP\Moncash\Moncash**.
     *
     * Creates a new configuration instance. Any unspecified values will
     * automatically be fetched from the host environment
     *
     * @param  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'endpoint': string, 'restApi': string, 'gatewayBase': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'idempotenceKeyMaker': IdempotenceKeyMaker
     * }  $config  an array of configuration values  you want to use to override teh defaul for this instance
     *
     * @throws SecretManagerException
     *
     * @example `$configuration = new Configuration(['lang'=>'ht']);`
     *
     * @since    1.0.0
     */
    public function __construct(
        #[ArrayShape([
            'mode'                => 'string',
            'lang'                => 'string',
            'clientSecret'        => 'string',
            'clientId'            => 'string',
            'businessKey'         => 'string',
            'endpoint'            => 'string',
            'restApi'             => 'string',
            'gatewayBase'         => 'string',
            'timeout'             => 'string',
            'secretManager'       => SecretManager::class,
            'tokenMachine'        => TokenMachine::class,
            'phoneValidation'     => PhoneValidation::class,
            'idempotenceKeyMaker' => IdempotenceKeyMaker::class,
        ])]
        array $config = []
    ) {
        $this->update($config);
    }

    /**
     * @param  bool  $reload  ⚠ If true it will overwrite the Host Variables with the value in the `.env` file
     * @param  string  $path  The path to the .env file
     *
     * @return  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'endpoint': string, 'restApi': string, 'gatewayBase': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'idempotenceKeyMaker': IdempotenceKeyMaker
     * }
     * @throws ConfigurationException
     */
    public static function getHostConfiguration(bool $reload = false, string $path = __DIR__.'/../../'): array
    {
        if ($reload) {
            self::loadHostConfig($path);
        }

        return [
            'mode'                => $_ENV['MONCASH_MODE'],
            'lang'                => $_ENV['MONCASH_LANG'],
            'clientSecret'        => $_ENV['MONCASH_CLIENT_SECRET'],
            'clientId'            => $_ENV['MONCASH_CLIENT_ID'],
            'businessKey'         => $_ENV['MONCASH_BUSINESS_KEY'],
            'timeout'             => $_ENV['MONCASH_TIMEOUT'],
            'secretManager'       => $_ENV['MONCASH_SECRET_MANAGER'],
            'tokenMachine'        => $_ENV['MONCASH_TOKEN_MACHINE'],
            'phoneValidation'     => $_ENV['MONCASH_PHONE_VALIDATION'],
            'idempotenceKeyMaker' => $_ENV['MONCASH_IDEMPOTENCE_MAKER'],
        ];
    }

    /**
     * Loads the `.env` file into the host.
     *
     * ⚠ This will affect the host current state
     *
     * @param  string|null  $path  The path to the .env file
     *
     * @return void
     * @throws ConfigurationException
     */
    public static function loadHostConfig(null|string $path = __DIR__.'/../../'): void
    {
        try {
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
        } catch (InvalidPathException $e) {
            throw new ConfigurationException("Could not load .env file from path [$path]", 0, $e);
        }

    }


    /**
     * @param  string|SecretManager|null  $manager
     *
     * @return void
     * @throws SecretManagerException
     */
    public function setSecretManager(string|SecretManager|null $manager): void
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

    /**
     * Checks if the configuration is set for the production host of Moncash
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->mode === 'production';
    }

    /**
     * @param  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'endpoint': string, 'restApi': string, 'gatewayBase': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'idempotenceKeyMaker': IdempotenceKeyMaker
     * }  $config
     *
     * @return $this
     * @throws SecretManagerException
     */
    public function update(
        #[ArrayShape([
            'mode'                => 'string',
            'lang'                => 'string',
            'clientSecret'        => 'string',
            'clientId'            => 'string',
            'businessKey'         => 'string',
            'endpoint'            => 'string',
            'restApi'             => 'string',
            'gatewayBase'         => 'string',
            'timeout'             => 'string',
            'secretManager'       => SecretManager::class,
            'tokenMachine'        => TokenMachine::class,
            'phoneValidation'     => PhoneValidation::class,
            'idempotenceKeyMaker' => IdempotenceKeyMaker::class,
        ])]
        array $config
    ): Configuration {

        $this->clientId     = $config['clientId'] ?? $this->clientId ?? getenv('MONCASH_CLIENT_ID');
        $this->clientSecret = $config['clientSecret'] ?? $this->clientSecret ?? getenv('MONCASH_CLIENT_SECRET');
        $this->businessKey  = $config['businessKey'] ??
                              $this->businessKey ??
                              getenv('MONCASH_BUSINESS_KEY') ?? 'sandbox';
        $this->mode         = $config['mode'] ?? $this->mode ?? getenv('MONCASH_MODE') ?? 'sandbox';
        $this->timeout      = $config['timeout'] ?? $this->timeout ?? 60;

        $this->setSecretManager($config['secretManager'] ?? $this->secretManager ?? null);

        return $this;
    }

    /**
     * @param  mixed  $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * @param  mixed  $offset
     *
     * @return IdempotenceKeyMaker|PhoneValidation|SecretManager|TokenMachine|mixed|string|null
     */
    public function offsetGet(
        #[ExpectedValues([
            'mode', 'clientSecret', 'clientId', 'businessKey', 'endpoint', 'restApi', 'gatewayBase', 'timeout',
            'secretManager', 'tokenMachine', 'phoneValidation', 'idempotenceKeyMaker'
        ])]
        mixed $offset
    ): mixed {
        return $this->array[$offset] ?? null;
    }

    /**
     * @throws ConfigurationException
     */
    public function offsetSet(
        #[ExpectedValues([
            'mode', 'lang', 'clientSecret', 'clientId', 'businessKey', 'endpoint', 'restApi', 'gatewayBase', 'timeout',
            'secretManager', 'tokenMachine', 'phoneValidation', 'idempotenceKeyMaker'
        ])]
        mixed $offset,
        mixed $value
    ) {
        if (is_null($offset)) {
            throw new ConfigurationException('Invalid parameter');
        } else {
            switch ($offset) {
                case 'phoneValidation':
                case 'secretManager':
                case 'idempotenceKeyMaker':
                case 'tokenMachine' :
                default :
            }
            $this->array[$offset] = $value;
        }
    }

    /**
     * @param  mixed  $offset
     *
     * @return void
     */
    public function offsetUnset(
        #[ExpectedValues([
            'mode', 'lang', 'clientSecret', 'clientId', 'businessKey', 'endpoint', 'restApi', 'gatewayBase', 'timeout',
            'secretManager', 'tokenMachine', 'phoneValidation', 'idempotenceKeyMaker'
        ])]// bug with PhpStorm with constants  [WI-56028]
        mixed $offset
    ): void {
        unset($this->array[$offset]);
        //TODO replace with default value
    }

    /**
     * Hide secret in debug stacks
     * @return array
     * @example 1+1=2
     *
     */
    public function __debugInfo()
    {

        $vars = get_object_vars($this);

        foreach ($vars as $prop => $value) {

            try {
                $rp = new ReflectionProperty(__CLASS__, $prop);
                if ($rp->name === 'clientSecret') {
                    unset($vars[$prop]);
                }
            } catch (ReflectionException $e) {
                // Silent fail
            }

        }

        return $vars;
    }
}
