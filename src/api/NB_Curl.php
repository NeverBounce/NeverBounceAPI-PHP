<?php namespace NeverBounce\API;

trait NB_Curl
{
    /**
     * @var string The base url for api requests
     */
    protected $apiBase = 'https://%s.neverbounce.com/%s/';

    /**
     * @var resource cURL object
     */
    public $curl;

    /**
     * @var string Raw response from cURL request
     */
    public $response_raw;

    /**
     * @var \stdClass JSON Decoded response from cURL request
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
     * @var bool Indicates if expected response should be json
     */
    protected $json = true;

    /**
     *
     * @param string $endpoint string Endpoint to use
     * @param array $data Post data for endpoint
     *
     * @throws \NeverBounce\API\NB_Exception
     */
    private function _request($endpoint, array $data = [], $json = true)
    {
        if ($endpoint == null) {
            throw new NB_Exception('No endpoint was supplied');
        }

        if ($json === false)
            $this->json = false;

        // Start request
        $this->curl = curl_init(sprintf($this->apiBase, NB_Auth::auth()->router(), NB_Auth::auth()->version()) . $endpoint);

        if ($this->debug) {
            $this->set_opt(CURLOPT_VERBOSE, true);
        } // Debug mode

        $this->set_opt(CURLOPT_SSL_VERIFYPEER, false);
        $this->set_opt(CURLOPT_HEADER, false);
        $this->set_opt(CURLOPT_RETURNTRANSFER, true);
        $this->set_opt(CURLOPT_POST, true);
        $this->set_opt(CURLOPT_POSTFIELDS, http_build_query($data));
        $this->set_opt(CURLOPT_TIMEOUT, NB_Auth::auth()->timeout());
    }

    /**
     * Executes a new curl request
     */
    private function exec_curl()
    {
        $this->response_raw = curl_exec($this->curl);
        $this->response = json_decode($this->response_raw);

        if (($this->json && (!is_object($this->response) || !$this->response->success))
            || (!$this->json && is_object($this->response)))
            $this->handleError();

        $this->close_curl();
    }

    /**
     * Sets cURL options
     *
     * @param $property string CURLOPT to set
     * @param $value string Value to set
     */
    private function set_opt($property, $value)
    {
        curl_setopt($this->curl, $property, $value);
    }

    /**
     * Closes current cURL connection
     */
    private function close_curl()
    {
        curl_close($this->curl);
    }

    /**
     * Toggles cURL verbose headers
     *
     * @param bool $arg
     * @return $this
     */
    public function setDebug($arg = null)
    {
        $this->debug = ($arg !== null) ? $arg : !$this->debug;

        return $this;
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

        if (!is_object($this->response) || !isset($this->response->error_code))
            throw new NB_Exception("Internal API error. " . $this->response_raw);

        switch ($this->response->error_code) {
            case 4:
                throw new NB_Exception("Insufficient credits to run this request.");
                break;
            case 3:
                throw new NB_Exception("Invalid Job. " . $this->response->error_msg);
                break;
            case 2:
                throw new NB_Exception("Malformed request. " . $this->response->error_msg);
                break;
            case 1:
                throw new NB_Exception("Authorization failure. " . $this->response->error_msg);
                break;
            case 0:
            default:
                throw new NB_Exception("Internal API error. " . $this->response->error_msg);
        }
    }
}
