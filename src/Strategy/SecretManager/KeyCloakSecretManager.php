<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

class KeyCloakSecretManager implements SecretManagerInterface
{
    /**@inheritdoc */
    function check(): bool|SecretManagerException
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
}
