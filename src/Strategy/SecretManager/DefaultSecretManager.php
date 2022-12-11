<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

use Dotenv\Dotenv;
use Exception;
use Fruitsbytes\PHP\MonCash\Strategy\StrategyException;

/**
 * Get Secret from .env file
 */
class DefaultSecretManager implements SecretManagerInterface
{

    const DEFAULT_ENV_PATH = '../../';
    /**
     * @param  string|null  $path  path to the `.env` file
     *
     * @inheritdoc
     * @throws StrategyException
     */
    public function __construct(string $path = self::DEFAULT_ENV_PATH)
    {
        $this->check($path);
    }

    /**
     * @param  string  $path  path to the `.env` file
     *
     * @inheritdoc
     */
    public function check(string $path = self::DEFAULT_ENV_PATH): bool
    {

        if (class_exists('Dotenv\Dotenv') === false) {
            throw new SecretManagerException("Could not find package vlucas\phpdotenv");
        }

        try {
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
        } catch (Exception $e) {
//            throw new SecretManagerException("Could not load .env file from path [$path]", 0, $e);
        }

        return false;
    }

    /**@inheritdoc */
    public function getSecret(): string
    {
        return $_ENV['MONCASH_CLIENT_SECRET'];
    }


}
