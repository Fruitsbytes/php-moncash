<?php

namespace Fruitsbytes\PHP\MonCash\API;

/**
 * Order DTO class
 */
class Order
{
    public float $amount;

    /**
     * @param  float  $amount  the price will be rounded up since the API only accepts integers
     * @param  string|null  $id
     */
    public function __construct(float $amount, public ?string $id = '')
    {
        $this->amount = ceil($amount);
    }
}
