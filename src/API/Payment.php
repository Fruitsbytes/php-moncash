<?php

namespace Fruitsbytes\PHP\MonCash\API;

use DateTime;
use Exception;


/**
 *  Payment DTO class
 */
class Payment
{

    public DateTime $expirationDate;
    public int $expirationInterval;

    /**
     * @throws PaymentException
     */
    public function __construct(
        public Order $order,
        public string $creation,
        public string $expiration,
        public string $token,
    ) {
        try {
            $this->expirationInterval = $this->setExpirationDate();
        } catch (Exception $e) {
            throw  new PaymentException('Could transfert payment data', 0, $e);
        }

    }

    /**
     * Set the expiration date based on $this->creation and  $this->expiration
     *
     * @return int interval in secondes between the creation and expiration dates
     * @throws Exception
     */
    private function setExpirationDate(): int
    {

        $interval = DateTime::createFromFormat('Y-m-d H:i:s:v',
                $this->expiration)->getTimestamp() - DateTime::createFromFormat('Y-m-d H:i:s:v',
                $this->creation)->getTimestamp();

        $this->expirationDate = new DateTime('+'.$interval.' seconds');

        return $interval;
    }

    /**
     * Parse the response from the CreatePayment into a Payment
     *
     * @param  Order  $order
     * @param  array{
     *                  mode: string,
     *                  path: string,
     *                  payment_token: array{
     *                                          expired: string,
     *                                          created: string,
     *                                          token: string
     *                                      },
     *                  timestamp: int,
     *                  status: int
     *              }  $array
     *
     * @return Payment
     * @throws PaymentException
     */
    public static function parse(Order $order, array $array): Payment
    {
        return new Payment(
            $order,
            $array['payment_token']['created'],
            $array['payment_token']['expired'],
            $array['payment_token']['token'],
        );
    }
}
