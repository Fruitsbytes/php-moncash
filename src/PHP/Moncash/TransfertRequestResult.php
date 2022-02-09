<?php

namespace Fruitsbytes\PHP\Moncash;

class TransfertRequestResult {
	/**
	 * @var string
	 */
	public $path;
	/**
	 * @var Transfert
	 */
	public $transfert;
	/**
	 * @var integer
	 */
	public $timestamp;
	/**
	 * @var integer HTTP_OK or else
	 */
	public $status;
}
