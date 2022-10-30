<?php

namespace Fruitsbytes\PHP\Moncash;

class Transfert
{
    /**
     * @var string
     */
    public $transaction_id;
    /**
     * @var float
     */
    public float $amount;
    /**
     * @var string
     */
    public string $receiver;
    /**
     * @var string
     */
    public string $message;
    /**
     * @var string
     */
    public string $desc;
}
