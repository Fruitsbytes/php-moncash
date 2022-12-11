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
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\SecretManager\\SecretManagerInterface",
            "namespace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\SecretManager",
            "propertyName"   => 'secretManager'
        ],
        'ToKenMachine'     => [
            "exceptionClass" => TokenMachineException::class,
            "default"        => FileTokenMachine::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\TokenMachine\\TokenMachineInterface",
            "namespace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\TokenMachine",
            "propertyName"   => 'tokenMachine'
        ],
        'OrderIdGenerator' => [
            "exceptionClass" => OrderIdGeneratorException::class,
            "default"        => UUIDOrderIdGenerator::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator\\OrderIdGeneratorInterface",
            "namespace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\OrderIdGenerator",
            "propertyName"   => 'orderIdGenerator'
        ],
        'PhoneValidation'  => [
            "exceptionClass" => PhoneValidationException::class,
            "default"        => DefaultHaitianPhoneValidation::class,
            "interface"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\PhoneValidation\\PhoneValidationInterface",
            "namespace"      => "Fruitsbytes\\PHP\\MonCash\\Strategy\\PhoneValidation",
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
    private static function getHostConfiguration(string $path = '../../'): array
    {

        self::loadHostConfig($path);

        return [
            'mode'             => $_ENV['MONCASH_MODE'] ?? self::DEFAULT_CONFIG['mode'],
            'lang'             => $_ENV['MONCASH_LANG'] ?? self::DEFAULT_CONFIG['lang'],
            'clientSecret'     => $_ENV['MONCASH_CLIENT_SECRET'] ?? self::DEFAULT_CONFIG['clientSecret'],
            'clientId'         => $_ENV['MONCASH_CLIENT_ID'] ?? self::DEFAULT_CONFIG['clientId'],
            'businessKey'      => $_ENV['MONCASH_BUSINESS_KEY'] ?? self::DEFAULT_CONFIG['businessKey'],
            'rsaPath'          => $_ENV['MONCASH_RSA_KEY_PATH'] ?? self::DEFAULT_CONFIG ['rsaPath'],
            'timeout'          => $_ENV['MONCASH_TIMEOUT'] ?? self::DEFAULT_CONFIG ['timeout'],
            'secretManager'    => $_ENV['MONCASH_SECRET_MANAGER'] ?? self::DEFAULT_CONFIG['secretManager'],
            'tokenMachine'     => $_ENV['MONCASH_TOKEN_MACHINE'] ?? self::DEFAULT_CONFIG['tokenMachine'],
            'phoneValidation'  => $_ENV['MONCASH_PHONE_VALIDATION'] ?? self::DEFAULT_CONFIG['phoneValidation'],
            'orderIdGenerator' => $_ENV['MONCASH_ORDER_ID_GENERATOR'] ?? self::DEFAULT_CONFIG['orderIdGenerator'],
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
     * @param  string|SecretManager  $manager
     *
     * @return void
     * @throws SecretManagerException|StrategyException
     */
    private function setSecretManager(string|SecretManager $manager = self::DEFAULT_CONFIG['secretManager']): void
    {
        $this->setStrategy($manager, 'SecretManager');
    }


    /**
     * @throws StrategyException|TokenMachineException
     */
    private function setToKenMachine(string|ToKenMachine $tokenMachine = self::DEFAULT_CONFIG['tokenMachine'])
    {
        $this->setStrategy($tokenMachine, 'ToKenMachine');
    }

    /**
     * @throws StrategyException|PhoneValidationException
     */
    private function setPhoneValidation(
        string|PhoneValidation $phoneValidation = self::DEFAULT_CONFIG['phoneValidation']
    ) {
        $this->setStrategy($phoneValidation, 'PhoneValidation');
    }

    /**
     * @throws StrategyException|OrderIdGeneratorException
     */
    private function setOrderIdGenerator(
        string|OrderIdGenerator $orderIdGenerator = self::DEFAULT_CONFIG['orderIdGenerator']
    ) {
        $this->setStrategy($orderIdGenerator, 'OrderIdGenerator');
    }

    /**
     * @param  mixed  $strategy
     *
     * @return string
     * @throws StrategyException
     */
    private static function getStrategyType(mixed $strategy): string
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
     * @param  string|StrategyInterface  $strategy
     * @param  string|null  $type
     *
     * @return void
     * @throws StrategyException
     */
    private function setStrategy(string|StrategyInterface $strategy, string|null $type = null): void
    {
        if (empty($type)) {  // Find Strategy Interface to know where to store it
            $type = self::getStrategyType($strategy);
        }

        if (
            is_subclass_of($strategy, StrategyInterface::class) === false ||
            empty(self::STRATEGY_PACKAGE[$type])
        ) {
            throw new StrategyException('The provided type does not implement StrategyInterface');
        }

        $package = self::STRATEGY_PACKAGE[$type];

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
                throw new $package['exceptionClass']('INVALID 0');
            }

            $object = new $class();

        } else {
            throw new $package['exceptionClass']('INVALID 1');
        }

        $this[$package['propertyName']] = $object;
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
        array|Configuration $config
    ): Configuration {

//        $hostConfig = self::getHostConfiguration();

        $this->mode = $config['mode'] ?? $this->mode ?? 'sandbox';
        if (in_array($this->mode, ['production', 'live', 'sandbox']) === false) {
            throw new ConfigurationException('INVALID_MODE');
        }
        $this->mode = $this->mode === 'sandbox' ? $this->mode : 'production';


        $this->clientId        = $config['clientId'] ?? $this->clientId ?? "";
        $this->lang            = $config['lang'] ?? $this->lang ?? "";
        $this->businessKey     = $config['businessKey'] ??
                                 $this->businessKey ?? "";
        $this->timeout         = $config['timeout'] ?? $this->timeout ?? 60;
        $this->rsaPath         = $config['rsaPath'] ?? $this->rsaPath ?? "";
        $this->gatewayBase     = self::GATEWAY_BASE[$this->mode];
        $this->gatewayMerchant = self::GATEWAY_MERCHANT[$this->mode];
        $this->restApi         = self::HOST_REST_API[$this->mode];

        try {
            $this->setSecretManager(
                $config['secretManager'] ?? $this->secretManager ?? self::DEFAULT_CONFIG['secretManager']
            );
            $this->setToKenMachine(
                $config['tokenMachine'] ?? $this->tokenMachine ?? self::DEFAULT_CONFIG['tokenMachine']
            );
            $this->setPhoneValidation(
                $config['phoneValidation'] ?? $this->phoneValidation ?? self::DEFAULT_CONFIG['phoneValidation']
            );
            $this->setOrderIdGenerator(
                $config['orderIdGenerator'] ?? $this->orderIdGenerator ?? self::DEFAULT_CONFIG['orderIdGenerator']
            );
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
    public function __toString(): string
    {
        $arr                 = $this->array;
        $arr['clientSecret'] = "******************";

        return json_encode($arr);
    }
}
