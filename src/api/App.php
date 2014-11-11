<?php namespace NeverBounce\API;

class App {

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
	 * @var string The base url for api requests
	 */
	protected $apiBase = 'https://api.neverbounce.com/';

	/**
	 * @var resource cURL object
	 */
	public $curl;

	/**
	 * @var string Raw response from cURL request
	 */
	public $response_raw;

	/**
	 * @var array JSON Decoded response from cURL request
	 */
	public $response;

	/**
	 * @var array cURL request info
	 */
	public $info;

	/**
	 * @var bool|string cURL errors if any
	 */
	protected $error = false;

	/**
	 * @var bool Dictates if debug info should be printed or not
	 */
	protected $debug = false;

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
		if ( ! function_exists( 'curl_init' ) ) {
			throw new \Exception( "The NeverBounce API requires the cURL PHP extension to be installed." );
		}

		if ( ! function_exists( 'json_decode' ) ) {
			throw new \Exception( "The NeverBounce API requires the JSON PHP extension to be installed." );
		}

		$this->secretKey = $secretKey;

		if ( !$this->secretKey ) {
			throw new \Exception( "You must supply your secretKey in order to use the NeverBounce API" );
		}

		$this->appID     = $appID;

		if ( !$this->appID ) {
			throw new \Exception( "You must supply your appID in order to use the NeverBounce API" );
		}

		$this->version   = $version;
	}

	/**
	 *
	 * @param string $endpoint string Endpoint to use
	 * @param array $data Post data for endpoint
	 */
	public function request($endpoint, array $data = []) {
		// Add appID and secretKey
		$data['app_id'] = $this->appID;
		$data['key'] = $this->secretKey;

		// Start request
		$this->curl = curl_init( $this->apiBase . $this->version . "/" . $endpoint . "/" );
		if ($this->debug) $this->set_opt(CURLOPT_VERBOSE, true);
		$this->set_opt(CURLOPT_SSL_VERIFYPEER, false);
		$this->set_opt(CURLOPT_HEADER, false);
		$this->set_opt(CURLOPT_RETURNTRANSFER, true);
		$this->set_opt(CURLOPT_POST, true);
		$this->set_opt(CURLOPT_POSTFIELDS, http_build_query($data));
		$this->exec_curl();
		$this->close_curl();
	}

	/**
	 * Executes a new curl request
	 * @return bool
	 */
	public function exec_curl() {
		$this->response_raw        = curl_exec( $this->curl );
		$this->response = json_decode( $this->response_raw );

		if (curl_error( $this->curl )) {
			$this->error = curl_error( $this->curl );
		} else {
			return true;
		}
	}

	/**
	 * Sets cURL options
	 * @param $property string CURLOPT to set
	 * @param $value string Value to set
	 */
	public function set_opt( $property, $value ) {
		curl_setopt( $this->curl, $property, $value );
	}

	/**
	 * Closes current cURL connection
	 */
	public function close_curl() {
		curl_close( $this->curl );
	}

	/**
	 * Toggle debug or set debug
	 * @param bool $arg
	 */
	public function setDebug($arg = null) {
		$this->debug = ($arg !== null) ? $arg : !$this->debug;
	}
}