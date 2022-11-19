<?php

namespace Fruitsbytes\PHP\MonCash\Strategy;


use Fruitsbytes\PHP\MonCash\Configuration\Configuration;

interface StrategyInterface
{

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool
     * @throws  StrategyException
     */
    function check(): bool;
}
