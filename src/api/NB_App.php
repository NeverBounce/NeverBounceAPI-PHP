<?php namespace NeverBounce\API;

use NeverBounce\API\NB_Auth;

/**
 * Class NB_App
 *
 * @package NeverBounce\API
 */
class NB_App {

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
	 *
	 * @param string $endpoint string Endpoint to use
	 * @param array $data Post data for endpoint
	 */
	public function request( $endpoint, array $data = [ ] ) {
		// Add appID and secretKey
		$data['app_id'] = NB_Auth::auth()->appID();
		$data['key']    = NB_Auth::auth()->secretKey();

		// Start request
		$this->curl = curl_init( $this->apiBase . NB_Auth::auth()->version() . "/" . $endpoint . "/" );
		if ( $this->debug ) {
			$this->set_opt( CURLOPT_VERBOSE, true );
		} // Debug mode
		$this->set_opt( CURLOPT_SSL_VERIFYPEER, false );
		$this->set_opt( CURLOPT_HEADER, false );
		$this->set_opt( CURLOPT_RETURNTRANSFER, true );
		$this->set_opt( CURLOPT_POST, true );
		$this->set_opt( CURLOPT_POSTFIELDS, http_build_query( $data ) );
		$this->exec_curl();
		$this->close_curl();
	}

	/**
	 * Executes a new curl request
	 *
	 * @return bool
	 */
	public function exec_curl() {
		$this->response_raw = curl_exec( $this->curl );
		$this->response     = json_decode( $this->response_raw );

		if ( curl_error( $this->curl ) ) {
			$this->error = curl_error( $this->curl );
		} else {
			return true;
		}
	}

	/**
	 * Sets cURL options
	 *
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
	 *
	 * @param bool $arg
	 */
	public function setDebug( $arg = null ) {
		$this->debug = ( $arg !== null ) ? $arg : ! $this->debug;
	}
}