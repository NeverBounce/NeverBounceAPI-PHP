<?php namespace NeverBounce\API;

/**
 * Class NB_Auth
 *
 * @package NeverBounce\API
 */
class NB_Auth {
	use NB_Curl;

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
	 * @var string The access token to pass with the request
	 */
	protected $access_token = null;

	/**
	 * @var string The time at which the token will expire
	 */
	protected $expires = null;

	/**
	 * @var string Url to api to use
	 * This is strictly for internal debugging, if not set then the default api would be used
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
	public static function auth( $secretKey = null, $appID = null, $version = 'v3' ) {
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
	public function __construct( $secretKey = null, $appID = null, $version = 'v3' ) {
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
	 * Gets an access token from the api
	 * @throws NB_Exception
	 */
	public function get_token() {
		/**
		 * Make sure secretKey has been supplied
		 */
		if ( $this->secretKey === null ) {
			throw new NB_Exception( "You must supply your secretKey in order to use the NeverBounce API" );
		}

		/**
		 * Make sure appID is supplied
		 */
		if ( $this->appID === null ) {
			throw new NB_Exception( "You must supply your appID in order to use the NeverBounce API" );
		}

		// Make request for token
		$this->_request( "access_token",
			[
				'grant_type'=>'client_credentials',
				'scope'=>'basic user',
			]);

		$this->set_opt(CURLOPT_USERPWD, $this->appID . ":" . $this->secretKey);
		$this->exec_curl();

		// Store the access token for later use
		$this->access_token = $this->response->access_token;
		$this->expires = time() + ($this->response->expires_in * 1000);
	}

	/**
	 * Executes a new curl request
	 *
	 * @return bool
	 */
	private function exec_curl() {
		$this->response_raw = curl_exec( $this->curl );
		$this->response     = json_decode( $this->response_raw );

		if(!is_object($this->response) || isset($this->response->error))
			$this->handleError();

		$this->close_curl();
	}

	/**
	 * Throw an error if curl request contains an error
	 *
	 * @throws \NeverBounce\API\NB_Exception
	 */
	private function handleError() {
		if ( curl_error( $this->curl ) ) {
			throw new NB_Exception( curl_error( $this->curl ) );
		}

		if(!is_object($this->response))
			throw new NB_Exception( "Internal API error. " . $this->response_raw );

		throw new NB_Exception($this->response->error_description);
	}

	/**
	 * Set Secrete Key
	 * @param $secretKey
	 */
	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;
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
	 * Returns api version
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Returns access token
	 * @return mixed
	 */
	public function token() {
		// TODO: Handle expired keys?
		return $this->access_token;
	}

	/**
	 * Sets API url
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