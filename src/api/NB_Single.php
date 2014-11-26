<?php namespace NeverBounce\API;

use NeverBounce\API\NB_App;

/**
 * Class NB_Single
 *
 * @package NeverBounce\API
 */
class NB_Single extends NB_App {

	/**
	 * @var \NeverBounce\API\NB_Single
	 */
	public static $instance;

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
	 * Type string definitions
	 *
	 * @var array
	 */
	protected $types = [
		'good'       => self::GOOD,
		'bad'        => self::BAD,
		'disposable' => self::DISPOSABLE,
		'catchall'   => self::CATACHALL,
		'unknown'    => self::UNKNOWN,
	];

	/**
	 * Instantiates class
	 *
	 * @return \NeverBounce\API\NB_Single
	 */
	public static function app() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * This performs a single validation
	 *
	 * @param string $email The email to verify
	 * @param string|null $fname The optional first name associated with email
	 * @param string|null $lname The optional last name associated with email
	 *
	 * @return $this
	 */
	public function verify( $email, $fname = null, $lname = null ) {
		$this->request( 'single', [ 'email' => $email, 'name_f' => $fname, 'name_l' => $lname ] );

		return $this;
	}

	/**
	 * Checks if the result is in the desired range.
	 *
	 * @param mixed $types acceptable result(s), string or array
	 *
	 * @return bool
	 */
	public function is( $types ) {
		if ( is_array( $types ) ) {
			foreach ( $types as $type ) {
				if ( $this->types[ $type ] == $this->response->result ) {
					return true;
				}
			}
		} else {
			if ( $this->types[ $types ] == $this->response->result ) {
				return true;
			}
		}

		return false;
	}
}