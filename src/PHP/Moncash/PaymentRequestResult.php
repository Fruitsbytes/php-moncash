<?php

namespace Fruitsbytes\PHP\Moncash;

class PaymentRequestResult {

	/**
	 * @var string
	 */
	public $redirect;
	/**
	 * @var string
	 */
	public $token;

	public function __construct( string $redirect, string $token ) {
		$this->redirect = $redirect;
		$this->token    = $token;
	}
}
