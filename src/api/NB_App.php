<?php namespace NeverBounce\API;

/**
 * Trait NB_App
 *
 * This trait is the base for the endpoint
 * classes. This class uses the NB_Curl class
 * to make the actual request. It also creates
 * the app() singleton method for the endpoint.
 *
 * @package NeverBounce\API
 */
trait NB_App
{
    use NB_Curl;

    /**
     * @var \NeverBounce\API\NB_App
     */
    public static $instance;

    /**
     * Checks for our dependencies, throws an error
     * if any of them are missing.
     *
     * @throws NB_Exception
     */
    public function __construct()
    {
        /**
         * Make sure the cURL extension exists
         */
        if (!function_exists('curl_init')) {
            throw new NB_Exception("The NeverBounce API requires the cURL PHP extension to be installed.");
        }

        /**
         * Make sure the JSON extension exists
         */
        if (!function_exists('json_decode')) {
            throw new NB_Exception("The NeverBounce API requires the JSON PHP extension to be installed.");
        }
    }

    /**
     * Instantiates class singleton
     *
     * @return \NeverBounce\API\NB_App
     */
    public static function app()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Creates a new cURL request for the specified
     * endpoint. This wrapper injects the access_token
     * into the request post data.
     *
     * @param $endpoint
     * @param array $data
	 * @param bool $json
     * @throws NB_Exception
     */
    protected function request($endpoint, $data = [], $json = true)
    {
        if (!NB_Auth::auth()->token())
            throw new NB_Exception("No access token is present, perhaps NB_Auth was not started");

        $data['access_token'] = NB_Auth::auth()->token();
        $this->_request($endpoint, $data, $json);
        $this->exec_curl();
    }
}
