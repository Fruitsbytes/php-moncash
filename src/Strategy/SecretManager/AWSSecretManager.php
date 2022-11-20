<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

/**
 * Get secret from AWS
 */
class AWSSecretManager implements SecretManagerInterface
{
    /**@inheritdoc */
    function check(): bool
    {
        // TODO: Implement check() method.
        return  false;
    }

    /**@inheritdoc */
    function getSecret(): string
    {
        // TODO: Implement getSecret() method.
        return '';
    }


    public function __construct()
    {
    }
}
