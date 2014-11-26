<?php namespace NeverBounce\API;

use NeverBounce\API\NB_App;

/**
 * Class NB_Account
 *
 * @package NeverBounce\API
 */
class NB_Account {
	use NB_App;

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