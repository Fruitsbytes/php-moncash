<?php

namespace Fruitsbytes\PHP\MonCash\API;

/**
 * Payment Error DTO class
 */
class PaymentError
{
    public function __construct(public string $code, public string $message)
    {
    }
}
