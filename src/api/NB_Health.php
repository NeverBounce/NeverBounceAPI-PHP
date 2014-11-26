<?php namespace NeverBounce\API;

use NeverBounce\API\NB_App;

/**
 * Class NB_Health
 *
 * @package NeverBounce\API
 */
class NB_Health extends NB_App {

	/**
	 * @var \NeverBounce\API\NB_Health
	 */
	public static $instance;

	/**
	 * Instantiates class
	 *
	 * @return \NeverBounce\API\NB_Health
	 */
	public static function app() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Performs a communication check to verify it can communicate with the api
	 *
	 * @return bool
	 */
	public function check() {
		$this->request( 'health' );

		if ( $this->response->result === 1 ) {
			return true;
		} else {
			return false;
		}
	}
}
