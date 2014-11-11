<?php namespace NeverBounce\API;

use NeverBounce\API\App;

class Account extends App {

	/**
	 * This returns your account details
	 */
	public function Account()
	{
		$this->request('account');
	}
}