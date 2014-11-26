<?php namespace NeverBounce\API;

/**
 * Class NB_Auth
 *
 * @package NeverBounce\API
 */
class NB_Auth {

	/**
	 * @var \NeverBounce\API\NB_Auth
	 */
	public static $instance;

	/**
	 * @var string Your NeverBounce secret key
	 */
	protected $secretKey;

	/**
	 * @var string Your NeverBounce app ID
	 */
	protected $appID;

	/**
	 * @var string The version of the api to use
	 */
	protected $version;

	/**
	 * Instantiates an auth object
	 *
	 * @param null $secretKey
	 * @param null $appID
	 * @param string $version
	 *
	 * @return \NeverBounce\API\NB_Auth
	 */
	public static function auth( $secretKey = null, $appID = null, $version = 'v1' ) {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self( $secretKey, $appID, $version );
		}

		return self::$instance;
	}

	/**
	 * Initialize a new API object with secret key, appId and Version
	 *
	 * If cURL or JSON are not found we throw an exception
	 *
	 * @param null $secretKey
	 * @param null $appID
	 * @param string $version Defaults to v1
	 *
	 * @throws \Exception
	 */
	public function __construct( $secretKey = null, $appID = null, $version = 'v1' ) {

		/**
		 * Make sure the cURL extension exists
		 */
		if ( ! function_exists( 'curl_init' ) ) {
			throw new NB_Exception( "The NeverBounce API requires the cURL PHP extension to be installed." );
		}

		/**
		 * Make sure the JSON extension exists
		 */
		if ( ! function_exists( 'json_decode' ) ) {
			throw new NB_Exception( "The NeverBounce API requires the JSON PHP extension to be installed." );
		}

		$this->secretKey = $secretKey;

		/**
		 * Make sure secretKey has been supplied
		 */
		if ( ! $this->secretKey ) {
			throw new NB_Exception( "You must supply your secretKey in order to use the NeverBounce API" );
		}

		$this->appID = $appID;

		/**
		 * Make sure appID is supplied
		 */
		if ( ! $this->appID ) {
			throw new NB_Exception( "You must supply your appID in order to use the NeverBounce API" );
		}

		$this->version = $version;
	}

	/**
	 * Returns secretKey
	 *
	 * @return null|string
	 */
	public function secretKey() {
		return $this->secretKey;
	}

	/**
	 * Returns appID
	 *
	 * @return null|string
	 */
	public function appID() {
		return $this->appID;
	}

	/**
	 * Returns api version
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}
}