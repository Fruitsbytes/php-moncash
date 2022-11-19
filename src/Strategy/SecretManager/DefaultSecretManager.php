<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Fruitsbytes\PHP\MonCash\Strategy\StrategyException;

/**
 * Get Secret from .env file
 */
class DefaultSecretManager implements SecretManagerInterface
{
    /**
     * @param  string|null  $path  path to the `.env` file
     *
     * @inheritdoc
     * @throws StrategyException
     */
    public function __construct(null|string $path = null)
    {
        $this->check($path);
    }

    /**
     * @param  string|null  $path  path to the `.env` file
     *
     * @inheritdoc
     */
    public function check(null|string $path = __DIR__.'/../src/'): bool
    {

        if (class_exists('Dotenv\Dotenv') === false) {
            throw new SecretManagerException("Could not find package vlucas\phpdotenv");
        }

        try {
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
        } catch (InvalidPathException $e) {
            throw new SecretManagerException("Could not load .env file from path [$path]", 0, $e);
        }

        return false;
    }

    /**@inheritdoc */
    public function getSecret(): string
    {
        return $_ENV['MONCASH_CLIENT_SECRET'];
    }


}
