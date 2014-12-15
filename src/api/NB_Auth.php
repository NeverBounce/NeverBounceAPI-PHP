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
	protected $secretKey = null;

	/**
	 * @var string Your NeverBounce app ID
	 */
	protected $appID = null;

	/**
	 * @var string The version of the api to use
	 */
	protected $version = null;

	/**
	 * @var string Url to api to use
	 * This is strictly for dev use, if not set then the default api would be used
	 */
	protected static $api = null;

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
	 * you may also set the apiID and secret key after initialization with
	 * the setters below
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
		$this->appID = $appID;
		$this->version = $version;
	}

	/**
	 * Set Secrete Key
	 * @param $secretKey
	 */
	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;
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
	 * Set AppID
	 * @param $appID
	 */
	public function setAppID($appID) {
		$this->appID = $appID;
	}

	/**
	 * Set version
	 * @param $version
	 */
	public function setVersion($version) {
		$this->version = $version;
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

	/**
	 * Sets API url
	 *
	 * @param $url
	 */
	public static function setAPI($url) {
		self::$api = $url;
	}

	/**
	 * Returns API url
	 * @return string
	 */
	public static function api() {
		return self::$api;
	}

}