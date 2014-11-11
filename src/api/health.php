<?php namespace NeverBounce\API;

use NeverBounce\API\App;

class Health extends App {

	/**
	 * This performs a basic health check to verify you can connect to the server.
	 */
	public function check()
	{
		$this->request('health');
	}
}