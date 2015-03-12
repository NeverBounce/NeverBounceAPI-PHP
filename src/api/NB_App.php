<?php namespace NeverBounce\API;

/**
 * Class NB_App
 *
 * @package NeverBounce\API
 */
trait NB_App {
	use NB_Curl;

	/**
     * @var \NeverBounce\API\NB_App
     */
    public static $instance;

	public function __construct() {
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

		if(!NB_Auth::auth()->token())
			NB_Auth::auth()->get_token();
	}

	/**
     * Instantiates class
     *
     * @return \NeverBounce\API\NB_App
     */
    public static function app() {
        if ( ! ( self::$instance instanceof self ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
	 * Creates a new curl request with access token
	 *
	 * @param $endpoint
	 * @param array $data
	 * @throws NB_Exception
	 */
	protected function request($endpoint, $data = []) {
		if(!NB_Auth::auth()->token())
			throw new NB_Exception("No access token is present, perhaps NB_Auth was not started");

		$data['access_token'] = NB_Auth::auth()->token();

		$this->_request($endpoint, $data);
		$this->exec_curl();
	}
}