<?php namespace NeverBounce\API;

/**
 * Class NB_Auth
 *
 * @package NeverBounce\API
 */
class NB_Auth
{
    use NB_Curl;

    /**
     * @var \NeverBounce\API\NB_Auth
     */
    public static $instance;

    /**
     * @var string The version of the api to use
     */
    protected $version = 'v3';

    /**
     * @var string Url to api to use
     * This is strictly for internal debugging, if not set then the default api would be used
     */
    protected $router = 'api';

    /**
     * @var string Your NeverBounce secret key
     */
    protected $secretKey = null;

    /**
     * @var string Your NeverBounce app ID
     */
    protected $appID = null;

    /**
     * @var string The access token to pass with the request
     */
    protected $access_token = null;

    /**
     * Instantiates an auth object
     * Simply pass in your credentials here, don't worry about
     * passing in a router or version if you were not given one.
     *
     * @param null $secretKey Your API secret key
     * @param null $appID Your API app id
     * @param null $router Your API subdomain http://<route>.neverbounce.com
     * @param string $version The api version to use
     *
     * @return \NeverBounce\API\NB_Auth
     */
    public static function auth($secretKey = null, $appID = null, $router = null, $version = null)
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        if ($secretKey)
            self::$instance->secretKey = $secretKey;

        if ($appID)
            self::$instance->appID = $appID;

        if ($router)
            self::$instance->router = $router;

        if ($version)
            self::$instance->version = $version;

        return self::$instance;
    }

    /**
     * Requests a new access token from the API,
     * we don't store the access token so we won't
     * worry about the expiration. On execution we
     * just request a new token.
     *
     * @throws NB_Exception
     */
    public function request_token()
    {
        /**
         * Make sure secretKey has been supplied
         */
        if ($this->secretKey === null) {
            throw new NB_Exception("You must supply your secretKey in order to use the NeverBounce API");
        }

        /**
         * Make sure appID is supplied
         */
        if ($this->appID === null) {
            throw new NB_Exception("You must supply your appID in order to use the NeverBounce API");
        }

        // Make request for token
        $this->_request("access_token",
            [
                'grant_type' => 'client_credentials',
                'scope' => 'basic user web',
            ]);

        $this->set_opt(CURLOPT_USERPWD, $this->appID . ":" . $this->secretKey);
        $this->exec_curl();

        // Store the access token for later use
        $this->access_token = $this->response->access_token;
    }

    /**
     * Executes a new curl request
     *
     * @return bool
     */
    private function exec_curl()
    {
        $this->response_raw = curl_exec($this->curl);
        $this->response = json_decode($this->response_raw);

        if (!is_object($this->response) || isset($this->response->error))
            $this->handleError();

        $this->close_curl();
    }

    /**
     * Throw an error if curl request contains an error
     *
     * @throws \NeverBounce\API\NB_Exception
     */
    private function handleError()
    {
        if (curl_error($this->curl)) {
            throw new NB_Exception(curl_error($this->curl));
        }

        if (!is_object($this->response))
            throw new NB_Exception("Internal API error. " . $this->response_raw);

        throw new NB_Exception($this->response->error_description);
    }

    /**
     * Returns API version
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * Returns API url
     * @return string
     */
    public function router()
    {
        return $this->router;
    }

    /**
     * Returns access token
     * @return string
     */
    public function token()
    {
        if (empty($this->access_token))
            $this->request_token();

        return $this->access_token;
    }
}