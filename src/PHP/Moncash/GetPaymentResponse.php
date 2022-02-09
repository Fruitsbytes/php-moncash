<?php

namespace Fruitsbytes\PHP\Moncash;

class GetPaymentResponse {
	/**
	 * @var string
	 */
	public $path;
	/**
	 * @var Payment
	 */
	public $payment;
	/**
	 * @var integer
	 */
	public $timestamp;
	/**
	 * @var integer HTTP_OK or else
	 */
	public $status;
}
