<?php namespace NeverBounce\API;

use NeverBounce\API\NB_App;

/**
 * Class NB_Account
 *
 * @package NeverBounce\API
 */
class NB_Account extends NB_App {

	/**
	 * @var \NeverBounce\API\NB_Account
	 */
	public static $instance;

	/**
	 * Instantiates class
	 *
	 * @return \NeverBounce\API\NB_Account
	 */
	public static function app() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Gets account information
	 *
	 * @return $this
	 */
	public function check() {
		$this->request( 'account' );

		return $this;
	}

	/**
	 * Returns account balance
	 *
	 * @return int
	 */
	public function balance() {
		return (integer) $this->response->credits;
	}

	/**
	 * Returns number of completed jobs
	 *
	 * @return int
	 */
	public function complete() {
		return (integer) $this->response->jobs_completed;
	}

	/**
	 * Returns number of jobs currently being processed
	 *
	 * @return int
	 */
	public function processing() {
		return (integer) $this->response->jobs_processing;
	}
}