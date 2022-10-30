<?php

namespace Fruitsbytes\PHP\Moncash;

class GetPaymentResponse
{
    /**
     * @var string
     */
    public string $path;
    /**
     * @var Payment
     */
    public Payment $payment;
    /**
     * @var integer
     */
    public int $timestamp;
    /**
     * @var integer HTTP_OK or else
     */
    public int $status;
}
