<?php

namespace Fruitsbytes\PHP\Moncash;


use GuzzleHttp\Psr7\Response;
use function json_decode;

class JsonAwareResponse extends Response {
	/**
	 * Cache for performance
	 * @var array
	 */
	private $json;

	public function getBody() {
		if ( $this->json ) {
			return $this->json;
		}
		// get parent Body stream
		$body = parent::getBody();

		// if JSON HTTP header detected - then decode
		if ( false !== strpos( $this->getHeaderLine( 'Content-Type' ), 'application/json' ) ) {
			return $this->json = json_decode( $body, true );
		}

		return $body;
	}
}
