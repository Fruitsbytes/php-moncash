<?php

namespace Fruitsbytes\PHP\MonCash\API;

use Exception;

class Client extends API
{
    public Order $order;

    /**
     * @throws ClientException|PaymentException|APIException
     */
    public function createPayment(Order $order): Payment
    {
        $this->order = $order;

        if ( ! isset($order->id)) {
            try {
                $this->order->id = $this->configuration->orderIdGenerator->getNewID();
            } catch (Exception $e) {
                throw  new ClientException('Could not generate a new orderId', 0, $e);
            }
        }

        $text = $this->post('/v1/CreatePayment', [
            "amount" => $order->amount, "orderId" => $order->id
        ]);

        return Payment::parse($order, json_decode($text, true));
    }

    public function getRedirectUrlForPayment(Payment $payment): string
    {
        return $this->configuration->gatewayBase."Payment/Redirect?token=$payment->token";
    }

    public function redirectToPayment(Payment $payment)
    {
        header('Location: '.$this->getRedirectUrlForPayment($payment));
    }

    /**
     * @throws ClientException
     * @throws PaymentException
     * @throws APIException
     */
    public function createAndRedirect(Order $order)
    {
        $payment = $this->createPayment($order);

        $this->redirectToPayment($payment);
    }


    /**
     * If you don't have the TransactionId yet and would like to know if the transaction is successful
     * use the unique reference number from the oder. a.k.a. the orderId
     *
     * @throws APIException
     */
    public function getPaymentByOrderId(string|Order $orderOrId): PaymentFoundResponse
    {

        $text = $this->post('/v1/RetrieveOrderPayment', [
            "orderId" => is_string($orderOrId) ? $orderOrId : $orderOrId->id
        ]);

        return PaymentFoundResponse::parse(json_decode($text, true));

    }

    /**
     * @throws APIException
     */
    public function getPaymentByTransactionId(string $id): PaymentFoundResponse
    {
        $text = $this->post('/v1/RetrieveOrderPayment', [
            "orderId" => $id
        ]);

        return PaymentFoundResponse::parse(json_decode($text, true));
    }

    /**
     * In the return URL or the Signal URL, you can use this to automatically get the transaction status(message)
     * To continue
     *
     * @throws ClientException
     * @throws APIException
     */
    public function catchPaymentfromURL(): PaymentFoundResponse
    {
        $tr = $_GET['transactionId'] ?? '';

        if (empty($tr)) {
            throw new ClientException('Missing transaction ID');
        }

        return $this->getPaymentByTransactionId($tr);
    }

}
