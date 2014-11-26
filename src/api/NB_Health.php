<?php namespace NeverBounce\API;

use NeverBounce\API\NB_App;

/**
 * Class NB_Health
 *
 * @package NeverBounce\API
 */
class NB_Health {
	use NB_App;

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
