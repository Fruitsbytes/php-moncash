<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\SecretManager;

interface SecretManagerInterface
{

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool| SecretManagerException
     */
    function check(): bool|SecretManagerException;

    /**
     * Get the CLIENT_SECRET
     * @return string
     */
    function getSecret(): string;
}
