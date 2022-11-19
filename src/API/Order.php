<?php

namespace Fruitsbytes\PHP\MonCash\API;

/**
 * Order DTO class
 */
class Order
{
    public float $amount;

    /**
     * @param  string  $id
     * @param  float  $amount  the price will be rounded up since the API only accepts integers
     */
    public function __construct(public string $id, float $amount)
    {
        $this->amount = ceil($amount);
    }
}
