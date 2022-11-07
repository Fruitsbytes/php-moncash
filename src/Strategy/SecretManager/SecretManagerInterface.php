<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\SecretManager;

use Fruitsbytes\PHP\MonCash\Strategy\StrategyInterface;

interface SecretManagerInterface extends StrategyInterface
{

    /**
     * @throws  SecretManagerException
     */
    function __construct();

    /**
     * @inheritdoc
     * @throws SecretManagerException
     */
    function check(): bool;

    /**
     * Get the CLIENT_SECRET
     * @return string
     */
    function getSecret(): string;
}
