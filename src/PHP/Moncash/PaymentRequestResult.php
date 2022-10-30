<?php

namespace Fruitsbytes\PHP\Moncash;

class PaymentRequestResult
{

    /**
     * @var string
     */
    public string $redirect;
    /**
     * @var string
     */
    public string $token;

    public function __construct(string $redirect, string $token)
    {
        $this->redirect = $redirect;
        $this->token    = $token;
    }
}
