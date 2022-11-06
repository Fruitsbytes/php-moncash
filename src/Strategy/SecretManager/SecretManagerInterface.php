<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

interface SecretManagerInterface
{

    /**
     * @throws  SecretManagerException
     */
    function __construct();

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool
     * @throws SecretManagerException
     */
    function check(): bool;

    /**
     * Get the CLIENT_SECRET
     * @return string
     */
    function getSecret(): string;
}
