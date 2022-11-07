<?php

namespace Fruitsbytes\PHP\MonCash\Strategy;


interface StrategyInterface
{
    /**
     * Check if all required dependencies and  configuration are available
     * @return bool
     * @throws  StrategyException
     */
    function check(): bool;
}
