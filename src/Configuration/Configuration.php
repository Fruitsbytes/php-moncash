<?php

namespace Fruitsbytes\PHP\MonCash\Configuration;

use ArrayObject;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator\OrderIdGeneratorException;
use Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator\OrderIdGeneratorInterface as OrderIdGenerator;
use Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator\UUIDOrderIdGenerator;
use Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation\DefaultHaitianPhoneValidation;
use Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation\LibPhoneValidation;
use Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation\PhoneValidationException;
use Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation\PhoneValidationInterface as PhoneValidation;
use Fruitsbytes\PHP\MonCash\Strategy\SecretManager\DefaultSecretManager;
use Fruitsbytes\PHP\MonCash\Strategy\SecretManager\SecretManagerException;
use Fruitsbytes\PHP\MonCash\Strategy\SecretManager\SecretManagerInterface as SecretManager;
use Fruitsbytes\PHP\MonCash\Strategy\StrategyException;
use Fruitsbytes\PHP\MonCash\Strategy\StrategyInterface;
use Fruitsbytes\PHP\MonCash\Strategy\TokenMachine\FileTokenMachine;
use Fruitsbytes\PHP\MonCash\Strategy\TokenMachine\TokenMachineException;
use Fruitsbytes\PHP\MonCash\Strategy\TokenMachine\TokenMachineInterface as TokenMachine;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use ReflectionException;
use ReflectionProperty;
use Stringable;


/**
 * Configuration helper fpr this library
 */
class Configuration extends ArrayObject implements Stringable
{

    /**
     * List of accepted attributes for the array
     */
    const VARS = [
        'mode', 'clientSecret', 'clientId', 'businessKey', 'endpoint', 'restApi', 'gatewayBase', 'timeout',
        'secretManager', 'tokenMachine', 'phoneValidation', 'orderIdGenerator'
    ];

    const HOST_REST_API = [
        "sandbox"    => "https://sandbox.moncashbutton.digicelgroup.com/Api",
        "production" => "https://moncashbutton.digicelgroup.com/Api"
    ];

    const GATEWAY_BASE = [
        "sandbox"    => "https://sandbox.moncashbutton.digicelgroup.com/Moncash-middleware",
        "production" => "https://moncashbutton.digicelgroup.com/Moncash-middleware"
    ];

    const GATEWAY_MERCHANT = [
        "sandbox"    => "https://sandbox.moncashbutton.digicelgroup.com/MerChantApi",
        "production" => "https://moncashbutton.digicelgroup.com/MerChantApi"
    ];

    const STRATEGY_PACKAGE = [
        'SecretManager'    => [
            "exceptionClass" => SecretManagerException::class,
            "default"        => DefaultSecretManager::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator\\OrderIdGeneratorInterface",
            "nameSpace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator",
            "propertyName"   => 'secretManager'
        ],
        'TokenMachine'     => [
            "exceptionClass" => TokenMachineException::class,
            "default"        => FileTokenMachine::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\TokenMachine\\TokenMachineInterface",
            "nameSpace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\TokenMachine",
            "propertyName"   => 'tokenMachine'
        ],
        'OrderIdGenerator' => [
            "exceptionClass" => OrderIdGeneratorException::class,
            "default"        => UUIDOrderIdGenerator::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator\\OrderIdGeneratorInterface",
            "nameSpace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator",
            "propertyName"   => 'orderIdGenerator'
        ],
        'PhoneValidation'  => [
            "exceptionClass" => PhoneValidationException::class,
            "default"        => DefaultHaitianPhoneValidation::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\PhoneValidation\\PhoneValidationInterface",
            "nameSpace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\PhoneValidation",
            "propertyName"   => 'phoneValidation'
        ],
    ];

    /**
     * The shape of the array
     */
    const CONFIGURATION_SHAPE = [
        'mode'             => 'string',
        'lang'             => 'string',
        'clientSecret'     => 'string',
        'clientId'         => 'string',
        'businessKey'      => 'string',
        'rsaPath'          => 'string',
        'timeout'          => 'string',
        'secretManager'    => SecretManager::class,
        'tokenMachine'     => TokenMachine::class,
        'phoneValidation'  => PhoneValidation::class,
        'orderIdGenerator' => OrderIdGenerator::class,
    ];

    const DEFAULT_CONFIG = [
        'mode'             => 'sandbox',
        'lang'             => 'en',
        'clientSecret'     => '',
        'clientId'         => '',
        'businessKey'      => '',
        'rsaPath'          => './rsa.text',
        'timeout'          => 60,
        'secretManager'    => DefaultSecretManager::class,
        'tokenMachine'     => FileTokenMachine::class,
        'phoneValidation'  => LibPhoneValidation::class,
        'orderIdGenerator' => UUIDOrderIdGenerator::class,
    ];


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
    #[\SensitiveParameter] // TODO PHP 8.2
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
    public string $restApi;

    /**
     * @var string
     */
    public string $gatewayBase;


    /**
     * @var string
     */
    public string $gatewayMerchant;

    /**
     * @var string
     */
    public string $rsaPath;

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
     * @var OrderIdGenerator
     */
    public OrderIdGenerator $orderIdGenerator;

    /**
     * @var  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string,'lang': string,
     *     'businessKey': string, 'rsaPath': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'orderIdGenerator': OrderIdGenerator
     * }
     */
    #[ArrayShape([
        'mode'             => 'string',
        'lang'             => 'string',
        'clientSecret'     => 'string',
        'clientId'         => 'string',
        'businessKey'      => 'string',
        'rsaPath'          => 'string',
        'timeout'          => 'string',
        'secretManager'    => SecretManager::class,
        'tokenMachine'     => TokenMachine::class,
        'phoneValidation'  => PhoneValidation::class,
        'orderIdGenerator' => OrderIdGenerator::class,
    ])]
    private array $array = [];

    /**
     * Configuration instance for the **Fruitsbytes\PHP\Moncash\Moncash**.
     *
     * Creates a new configuration instance. Any unspecified values will
     * automatically be fetched from the host environment
     *
     * @param  Configuration | array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'rsaPath': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'orderIdGenerator': OrderIdGenerator
     * }  $config  an array of configuration values  you want to use to override teh defaul for this instance
     *
     * @throws ConfigurationException
     *
     * @example `$configuration = new Configuration(['lang'=>'ht']);`
     *
     * @since    1.0.0
     */
    public function __construct(

        #[ArrayShape([
            'mode'             => 'string',
            'lang'             => 'string',
            'clientSecret'     => 'string',
            'clientId'         => 'string',
            'businessKey'      => 'string',
            'rsaPath'          => 'string',
            'timeout'          => 'string',
            'secretManager'    => SecretManager::class,
            'tokenMachine'     => TokenMachine::class,
            'phoneValidation'  => PhoneValidation::class,
            'orderIdGenerator' => OrderIdGenerator::class,
        ])]
        Configuration|array $config = []
    ) {
        parent::__construct($config, self::ARRAY_AS_PROPS);
        $this->update($config);
    }

    /**
     * @param  bool  $reload  ⚠ If true it will overwrite the Host Variables with the value in the `.env` file
     * @param  string  $path  The path to the .env file
     *
     * @return  array{
     *     'mode': string, 'clientSecret' : string, 'clientId': string, 'lang': string,
     *     'businessKey': string, 'rsaPath': string,'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'orderIdGenerator': OrderIdGenerator
     * }
     * @throws ConfigurationException
     */
    private static function getHostConfiguration(bool $reload = false, string $path = __DIR__.'/../src/'): array
    {
        if ($reload) {
            self::loadHostConfig($path);
        }

        return [
            'mode'             => $_ENV['MONCASH_MODE'],
            'lang'             => $_ENV['MONCASH_LANG'],
            'clientSecret'     => $_ENV['MONCASH_CLIENT_SECRET'],
            'clientId'         => $_ENV['MONCASH_CLIENT_ID'],
            'businessKey'      => $_ENV['MONCASH_BUSINESS_KEY'],
            'rsaPath'          => $_ENV['MONCASH_RSA_KEY_PATH'],
            'timeout'          => $_ENV['MONCASH_TIMEOUT'],
            'secretManager'    => $_ENV['MONCASH_SECRET_MANAGER'],
            'tokenMachine'     => $_ENV['MONCASH_TOKEN_MACHINE'],
            'phoneValidation'  => $_ENV['MONCASH_PHONE_VALIDATION'],
            'orderIdGenerator' => $_ENV['MONCASH_IDEMPOTENCE_MAKER'],
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
    private static function loadHostConfig(null|string $path = __DIR__.'/../src/'): void
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
     * @throws SecretManagerException|StrategyException
     */
    private function setSecretManager(string|SecretManager|null $manager = null): void
    {
        $this->setStrategy($manager, 'SecretManager');
    }


    /**
     * @throws StrategyException|TokenMachineException
     */
    private function setToKenMachine(string|ToKenMachine|null $tokenMachine = null)
    {
        $this->setStrategy($tokenMachine, 'ToKenMachine');
    }

    /**
     * @throws StrategyException|PhoneValidationException
     */
    private function setPhoneValidation(string|PhoneValidation|null $phoneValidation = null)
    {
        $this->setStrategy($phoneValidation, 'PhoneValidation');
    }

    /**
     * @throws StrategyException|OrderIdGeneratorException
     */
    public function setOrderIdGenerator(string|OrderIdGenerator|null $orderIdGenerator = null)
    {
        $this->setStrategy($orderIdGenerator, 'OrderIdGenerator');
    }

    /**
     * @param  mixed  $strategy
     *
     * @return string
     * @throws StrategyException
     */
    public static function getStrategyType(mixed $strategy): string
    {

        if ((empty($strategy)) || is_subclass_of($strategy, StrategyInterface::class) === false) {
            throw new StrategyException('The provided strategy does not implement StrategyInterface(1).');
        } else {

            $found = [];
            foreach (
                [
                    'SecretManager', 'TokenMachine', 'OrderIdGenerator', 'PhoneValidation'
                ] as $strg
            ) {
                $namespacedInterface = "Fruitsbytes\\PHP\\MonCash\\Strategy\\$strg\\$strg"."Interface";
                if (
                    is_subclass_of($strategy, $namespacedInterface)
                ) {
                    $found[] = $strg;
                }
                if (count($found) > 1) {
                    throw new StrategyException(
                        'More than one Interface match. Specify the type in the second parameter.');
                }
            }
            if (empty($found)) {
                throw new StrategyException('Unrecognized strategy type for '.$strategy);
            }

            return $found[0];
        }
    }


    /**
     * @param  string|StrategyInterface|null  $strategy
     * @param  string|null  $type
     *
     * @return void
     * @throws StrategyException
     */
    private function setStrategy(string|StrategyInterface|null $strategy, string|null $type = null): void
    {
        if (empty($type)) {  // Find Strategy Interface to know where to store it
            $type = self::getStrategyType($strategy);
        }

        if (
            is_subclass_of($type, StrategyInterface::class) === false ||
            empty($package = self::STRATEGY_PACKAGE[$type])
        ) {
            throw new StrategyException('The provided type does not implement StrategyInterface');
        }

        if (is_object($strategy)) {
            $object = $strategy;
        } elseif (empty($strategy)) {
            $object = new $package['default']();
        } elseif (is_string($strategy)) {

            if (class_exists($strategy) && is_subclass_of($strategy, $package['interface'])) {
                $class = $strategy;
            } elseif (
                class_exists($package['namespace']."\\$strategy") &&
                is_subclass_of($package['namespace']."\\$strategy", $package['interface'])
            ) {
                $class = $package['namespace']."\\$strategy";
            } else {
                throw new $package['exception']('INVALID');
            }

            $object = new $class();

        } else {
            throw new $package['exception']('INVALID');
        }

        $this[$package['property']] = $object;
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
     *     'businessKey': string, 'rsaPath': string, 'timeout':float|int,
     *     'secretManager' : SecretManager,
     *     'tokenMachine': TokenMachine,
     *     'phoneValidation': PhoneValidation,
     *     'orderIdGenerator': OrderIdGenerator
     * }  $config
     *
     * @return $this
     * @throws ConfigurationException
     */
    public function update(
        #[ArrayShape([
            'mode'             => 'string',
            'lang'             => 'string',
            'clientSecret'     => 'string',
            'clientId'         => 'string',
            'rsaPath'          => 'string',
            'businessKey'      => 'string',
            'timeout'          => 'string',
            'secretManager'    => SecretManager::class,
            'tokenMachine'     => TokenMachine::class,
            'phoneValidation'  => PhoneValidation::class,
            'orderIdGenerator' => OrderIdGenerator::class,
        ])]
        array $config
    ): Configuration {

        $hostConfig = self::getHostConfiguration();

        $this->mode = $config['mode'] ?? $this->mode ?? $hostConfig['mode'] ?? 'sandbox';
        if (in_array($this->mode, ['production', 'live', 'sandbox']) === false) {
            throw new ConfigurationException('INVALID_MODE');
        }
        $this->mode = $this->mode === 'sandbox' ? $this->mode : 'production';


        $this->clientId        = $config['clientId'] ?? $this->clientId ?? $hostConfig['clientId'];
        $this->lang            = $config['lang'] ?? $this->lang ?? $hostConfig['lang'];
        $this->businessKey     = $config['businessKey'] ??
                                 $this->businessKey ??
                                 $hostConfig['MONCASH_BUSINESS_KEY'];
        $this->timeout         = $config['timeout'] ?? $this->timeout ?? 60;
        $this->rsaPath         = $config['rsaPath'] ?? $this->rsaPath ?? $hostConfig['rsaPath'];
        $this->gatewayBase     = self::GATEWAY_BASE[$this->mode];
        $this->gatewayMerchant = self::GATEWAY_MERCHANT[$this->mode];
        $this->restApi         = self::HOST_REST_API[$this->mode];

        try {
            $this->setSecretManager($config['secretManager'] ?? $this->secretManager ?? null);
            $this->setToKenMachine($config['tokenMachine'] ?? $this->tokenMachine ?? null);
            $this->setPhoneValidation($config['phoneValidation'] ?? $this->phoneValidation ?? null);
            $this->setOrderIdGenerator($config['orderIdGenerator'] ?? $this->orderIdGenerator ?? null);
        } catch (StrategyException $e) {
            throw new ConfigurationException('FAILED_TO_SET_STRATEGY', 0, $e);
        }

        $this->clientSecret = $config['clientSecret'] ?? $this->clientSecret ?? $this->secretManager->getSecret();

        return $this;
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
            } catch (ReflectionException) {
                // Silent fail
            }

        }

        return $vars;
    }

    /**
     * @param  bool  $secure  Hide sensible values
     *
     * @inheritdoc
     */
    public function __toString(bool $secure = true): string
    {
        $arr = $this->array;
        if ($secure) {
            $arr['clientSecret'] = "******************";
        }

        return json_encode($arr);
    }
}
