<?php namespace NeverBounce\API;

use NeverBounce\API\App;

class Single extends App {

	/**
	 * Value of a verified good email
	 */
	const GOOD = 0;

	/**
	 * Value of a verified bad email
	 */
	const BAD = 1;

	/**
	 * Value of a disposable email
	 */
	const DISPOSABLE = 2;

	/**
	 * Value of a catchall email
	 */
	const CATACHALL = 3;

	/**
	 * Value of an unknown email
	 */
	const UNKNOWN = 4;

	/**
	 * This performs a single validation
	 * @param string $email The email to verify
	 * @param string|null $fname The optional first name associated with email
	 * @param string|null $lname The optional last name associated with email
	 */
	public function verify($email, $fname = null, $lname = null)
	{
		$this->request('single', ['email' => $email, 'name_f' => $fname, 'name_l' => $lname]);
	}

}