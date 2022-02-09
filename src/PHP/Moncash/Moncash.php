<?php

namespace Fruitsbytes\PHP\Moncash;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Moncash {
	/**
	 * @var string
	 */
	private $mode;
	/**
	 * @var string
	 */
	private $client_secret;
	/**
	 * @var string
	 */
	private $client_id;
	/**
	 * @var array
	 */
	private $credentials;
	/**
	 * @var bool
	 */
	private $isProduction;
	/**
	 * @var string
	 */
	private $endpoint;
	/**
	 * @var string
	 */
	private $rest_api;
	/**
	 * @var string
	 */
	private $gateway_base;
	/**
	 * @var Client
	 */
	private $client;
	/**
	 * @var string
	 */
	private $access_token;

	public function __construct(
		string $client_id,
		string $client_secret,
		string $mode = 'sandbox',
		float $timeout = 2.0
	) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->mode          = $mode;

		$this->isProduction = $mode === 'sandbox';

		$this->credentials = [
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'mode'          => $this->mode,
		];

		$this->isProduction = $this->credentials['mode'] === 'sandbox';

		if ( $this->isProduction ) {
			$this->endpoint = 'https://moncashbutton.digicelgroup.com';
		} else {
			$this->endpoint = 'https://sandbox.moncashbutton.digicelgroup.com';
		}

		$this->rest_api     = '/Api';
		$this->gateway_base = '/Moncash-middleware';

		$this->client = new Client( [
			'base_uri' => $this->endpoint,
			'timeout'  => $timeout,
			'headers'  => [ 'Accept' => 'application/json' ]
		] );


		$handler = $this->client->getConfig( 'handler' );
		$handler->push( Middleware::mapResponse( function ( ResponseInterface $response ) {
			return new JsonAwareResponse(
				$response->getStatusCode(),
				$response->getHeaders(),
				$response->getBody(),
				$response->getProtocolVersion(),
				$response->getReasonPhrase()
			);
		} ), 'json_decode_middleware' );
	}

	/**
	 * To call the resources of the Rest API MonCash, you must authenticate by including the bearer token in the
	 * Authorization header with the Bearer authentication scheme. The value is Bearer <Access-Token> or Basic
	 * <client_id>:<secret>. The client_id and client_secret can be generated from the business portal.
	 * @throws GuzzleException
	 */
	public function get_access_token() {

		$url = "$this->rest_api/oauth/token";

		$response = $this->client->post( $url, [
			'auth'  => [
				$this->credentials['client_id'],
				$this->credentials['client_secret']
			],
			'query' => [
				'grant_type' => 'client_credentials',
				'scope'      => 'read,write'
			]
		] );

		$response = $response->getBody();

		$this->access_token = $response['access_token'];
	}

	/**
	 * Check if token already exist if not get it
	 * @return void
	 * @throws MonCashException
	 */
	private function touchToken() {
		// TODO verify expiration
		if ( ! $this->access_token ) {
			try {
				$this->get_access_token();
			}
			catch ( Throwable $e ) {
				throw new MonCashException( $e->getMessage() );
			}

		}
	}


	/**
	 * To create a payment you must send the orderId and amount as HTTP POST with an
	 * Authorization Bearer Token. The response will be a JSON (or other format) with the
	 * parameters success and redirect URL to load the Payment Gateway of MonCash
	 * Middleware.
	 *
	 * @param float  $amount
	 * @param string $orderId
	 *
	 * @return PaymentRequestResult The redirection URL + token
	 * @throws MonCashException
	 */
	public function create_payment( float $amount, string $orderId ) {

		if ( $amount <= 10 ) {
			throw new MonCashException( 'amount should bye greater than 10' );
		}

		$this->touchToken();

		try {
			$payment = $this->client->post( "$this->rest_api/v1/CreatePayment", [
				[
					'auth' =>
						[ 'Bearer', $this->access_token ],
					'json' => [
						'amount'  => $amount,
						'orderId' => $orderId
					]
				]
			] );

			$token = $payment['payment_token']['token'];

			return new PaymentRequestResult(
				"$this->endpoint.$this->gateway_base/Payment/Redirect?token=" . $token,
				$token . ""
			);
		}
		catch ( Throwable $e ) {
			throw new MonCashException( $e->getMessage() );
		}

	}

	/**
	 * To get a payment details from the return URL business script you must send the transactionId and orderId as
	 * HTTP POST with an Authorization Bearer Token. The response will be a JSON (or other format) with the
	 * payment details.
	 *
	 * @param string $id Transaction ID
	 *
	 * @return TransfertRequestResult
	 * @throws MonCashException
	 */
	public function get_payment_by_transaction( string $id ) {

		$this->touchToken();

		try {
			/**
			 * @var $paymentResult TransfertRequestResult
			 */
			$paymentResult = $this->client->post( "$this->rest_api/v1/RetrieveTransactionPayment", [
				[
					'auth' =>
						[ 'Bearer', $this->access_token ],
					'json' => [
						'transactionId' => $id
					]
				]
			] )->getBody();

			if ( $paymentResult['status'] == 200 ) {
				return $paymentResult;
			} else {
				throw new MonCashException( 'Error' );
			}
		}
		catch ( Throwable $e ) {
			throw new MonCashException( $e->getMessage() );
		}
	}

	/**
	 * To get a payment details from the return URL business script you must send the transactionId and orderId as
	 * HTTP POST with an Authorization Bearer Token. The response will be a JSON (or other format) with the
	 * payment details.
	 *
	 * @param string $id
	 *
	 * @return TransfertRequestResult
	 * @throws MonCashException
	 */
	public function get_payment_by_order( string $id ) {

		$this->touchToken();

		try {
			/**
			 * @var $paymentResult TransfertRequestResult
			 */
			$paymentResult = $this->client->post( "$this->rest_api/v1/RetrieveOrderPayment", [
				[
					'auth' =>
						[ 'Bearer', $this->access_token ],
					'json' => [
						'transactionId' => $id
					]
				]
			] )->getBody();

			if ( $paymentResult['status'] == 200 ) {
				return $paymentResult;
			} else {
				throw new MonCashException( 'Error' );
			}
		}
		catch ( Throwable $e ) {
			throw new MonCashException( $e->getMessage() );
		}
	}

	/**
	 * @param int    $amount
	 * @param string $receiver
	 * @param string $description
	 *
	 * @return TransfertRequestResult
	 * @throws MonCashException
	 */
	public function transfer( int $amount, string $receiver, string $description = '' ) {
		$this->touchToken();

		try {
			/**
			 * @var $paymentResult TransfertRequestResult
			 */
			$paymentResult = $this->client->post( "$this->rest_api/v1/Transfert", [
				[
					'auth' =>
						[ 'Bearer', $this->access_token ],
					'json' => [
						'amount'   => $amount,
						'receiver' => $receiver,
						'desc'     => $description
					]
				]
			] )->getBody();

			if ( $paymentResult['status'] == 200 ) {
				return $paymentResult;
			} else {
				throw new MonCashException( 'Error' );
			}
		}
		catch ( Throwable $e ) {
			throw new MonCashException( $e->getMessage() );
		}
	}

}
