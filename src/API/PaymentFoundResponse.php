<?php

namespace Fruitsbytes\PHP\MonCash\API;

/**
 * DTO for the result from querying the API for a Payment
 */
class PaymentFoundResponse
{
    public function __construct(
        public string $reference,
        public string $transactionId,
        public string $cost,
        public string $message,
        public string $payer
    ) {
    }

    /**
     * @param  array{
     *                  path: string,
     *                  payment: array{
     *                      reference: string,
     *                      transaction_id: string,
     *                      cost: int,
     *                      message: string,
     *                      payer: number
     *                  },
     *                  timestamp: int,
     *                  status: int
     *              }  $array
     *
     * @return PaymentFoundResponse
     */
    public static function parse(array $array): PaymentFoundResponse
    {
        return new PaymentFoundResponse(
            $array['payment']['reference'],
            $array['payment']['transaction_id'],
            $array['payment']['cost'],
            $array['payment']['message'],
            $array['payment']['payer'],
        );
    }

    public function isSuccessful()
    {
        return $this->message === 'successful';
    }
}
