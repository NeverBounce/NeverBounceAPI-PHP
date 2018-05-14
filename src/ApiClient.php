<?php namespace NeverBounce;

use NeverBounce\Errors\AuthException;
use NeverBounce\Errors\BadReferrerException;
use NeverBounce\Errors\GeneralException;
use NeverBounce\Errors\HttpClientException;
use NeverBounce\Errors\ThrottleException;
use NeverBounce\HttpClient\CurlClient;
use NeverBounce\HttpClient\HttpClientInterface;

class ApiClient
{
    /**
     * @var self
     */
    static protected $lastInstance;

    /**
     * @var bool
     */
    static protected $debug = false;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string The base url for API requests
     */
    static protected $baseUrl = 'https://api.neverbounce.com/v4/';

    /**
     * @var int The maximum number of seconds to allow cURL functions to
     *     execute.
     */
    protected $timeout = 30;

    /**
     * @var string The content type for this request
     */
    protected $contentType = 'application/x-www-form-urlencoded';

    /**
     * @var array
     */
    protected $decodedResponse = [];

    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * @var
     */
    protected $statusCode = 0;

    /**
     * ApiClient constructor.
     * @param HttpClientInterface $clientInterface
     * @throws AuthException
     */
    public function __construct(HttpClientInterface $clientInterface = null)
    {
        // Check for a key
        if (Auth::getApiKey() === null) {
            throw new AuthException(
                'An API key has not been set yet; Use the '
                . 'NeverBounce\Auth::setApiKey($key) method to '
                . 'set the key before making a request.'
            );
        }

        $this->client = $clientInterface ?: new CurlClient();
    }

    /**
     * @param string $url Set the base URL to use for API requests. This method
     *     exists for development purposes
     */
    public static function setBaseUrl($url)
    {
        self::$baseUrl = $url;
    }

    /**
     * @return ApiClient
     */
    public static function getLastRequest()
    {
        return self::$lastInstance;
    }

    /**
     * @param enables debug mode (dumps out encoded params and response)
     */
    public static function debug()
    {
        self::$debug = true;
    }

    /**
     * @param integer $timeout Sets the maximum number of seconds to allow the
     *     request to execute.
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = max((int)$timeout, 0);
        return $this;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @return array
     */
    public function getDecodedResponse()
    {
        return $this->decodedResponse;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $contentType Content type for the request; either
     *     application/x-www-form-urlencoded or application/json
     * @return $this
     * @throws \NeverBounce\Errors\HttpClientException
     */
    public function setContentType($contentType)
    {
        $supported = in_array(
            $contentType,
            ['application/x-www-form-urlencoded', 'application/json'],
            true
        );

        // Throw exception if unsupported content type is specified
        if (!$supported) {
            throw new HttpClientException(
                'An unsupported content type was supplied. This API '
                . 'supports either \'application/x-www-form-urlencoded\' '
                . 'or \'application/json\'.'
            );
        }

        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return mixed
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public function request($method, $endpoint, array $params = [])
    {
        // Set API key parameter
        $params['key'] = Auth::getApiKey();

        // Encode parameters according to contentType
        $encodedParams = ($this->contentType === 'application/json') ? json_encode($params) : http_build_query($params);

        if (self::$debug) {
            var_dump($encodedParams);
        }

        // Base url + endpoint resolved
        $url = self::$baseUrl . $endpoint;

        // If this is a GET request append query to the end of the url
        if (strtoupper($method) === 'GET') {
            $this->client->init($url . '?' . $encodedParams);
        } else {
            // Assume all other requests are POST and set fields accordingly
            $this->client->init($url);
            $this->client->setOpt(CURLOPT_POSTFIELDS, $encodedParams);
            $this->client->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        }

        // Set options
        $this->client->setOpt(CURLOPT_HTTPHEADER, [
            'User-Agent: NeverBounce-PHPSdk/' . Utils::wrapperVersion(),
            'Content-Type: ' . $this->contentType
        ]);
        $this->client->setOpt(CURLOPT_TIMEOUT, $this->timeout);
        $this->client->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->client->setOpt(CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($headerLine, ":") === false) {
                return strlen($headerLine);
            }
            list($key, $value) = explode(":", trim($headerLine), 2);
            $this->responseHeaders[trim($key)] = trim($value);
            return strlen($headerLine);
        });

        $this->rawResponse = $this->client->execute();

        if (self::$debug) {
            var_dump($this->rawResponse);
        }

        // Catches curl errors
        if ($this->rawResponse === false) {
            $errno = $this->client->getErrno();
            $message = $this->client->getError();
            $this->client->close();
            $this->handleCurlError($url, $errno, $message);
        }

        // Get status code
        $this->statusCode = $this->client->getInfo(CURLINFO_HTTP_CODE);
        $this->client->close();
        return $this->response($this->rawResponse, $this->responseHeaders, $this->statusCode);
    }

    /**
     * @param string $url
     * @param integer $errno
     * @param string $message
     * @throws HttpClientException
     */
    protected function handleCurlError($url, $errno, $message)
    {
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to NeverBounce ($url).  Please check your "
                    . 'internet connection and try again.  If this problem persists, '
                    . 'you should';
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify NeverBounce's SSL certificate.  Please make sure "
                    . 'that your network is not intercepting certificates.  '
                    . "(Try going to $url in your browser.)  "
                    . 'If this problem persists,';
                break;
            default:
                $msg = 'Unexpected error communicating with NeverBounce.  '
                    . 'If this problem persists,';
        }
        $msg .= ' let us know at support@neverbounce.com.';
        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new HttpClientException($msg);
    }

    /**
     * Parses the response string and handles any errors
     * @param $respBody
     * @param $respCode
     * @return mixed
     * @throws AuthException
     * @throws BadReferrerException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws ThrottleException
     */
    protected function response($respBody, $respHeaders, $respCode)
    {
        // Handle non successful HTTP status codes
        if ($respCode > 400) {
            $type = $respCode > 500 ? 'Internal error' : 'Request error';
            throw new GeneralException(
                'The request to NeverBounce was unsuccessful '
                . 'Try the request again, if this error persists'
                . ' let us know at support@neverbounce.com.'
                . "\n\n($type [status $respCode: $respBody])"
            );
        }

        // Handle response based on Content-Type
        if ($respHeaders['Content-Type'] === 'application/json') {
            return $this->jsonResponse($respBody, $respHeaders, $respCode);
        }

        return $this->decodedResponse = $respBody;
    }

    /**
     * @param $respBody
     * @param $respHeaders
     * @param $respCode
     * @return mixed
     * @throws GeneralException
     */
    protected function jsonResponse($respBody, $respHeaders, $respCode)
    {
        $decoded = json_decode($respBody, true);

        // Check if the response was decoded properly
        if ($decoded === null) {
            throw new GeneralException(
                'The response from NeverBounce was unable '
                . 'to be parsed as json. Try the request '
                . 'again, if this error persists'
                . ' let us know at support@neverbounce.com.'
                . "\n\n(Internal error [status $respCode: $respBody])"
            );
        }

        // Check for missing status and error messages
        if (!isset($decoded['status']) || ($decoded['status'] !== 'success' && !isset($decoded['message']))) {
            throw new GeneralException(
                'The response from server is incomplete. '
                . 'Either a status code was not included or '
                . 'the an error was returned without an error '
                . 'message. Try the request again, if '
                . 'this error persists let us know at'
                . ' support@neverbounce.com.'
                . "\n\n(Internal error [status $respCode: $respBody])"
            );
        }

        // Handle other non success statuses
        if ($decoded['status'] !== 'success') {

            $exceptionLUT = [
                'auth_failure' => AuthException::class,
                'bad_referrer' => BadReferrerException::class,
                'general_failure' => GeneralException::class,
                'throttle_triggered' => ThrottleException::class,
            ];
            if(isset($exceptionLUT[$decoded['status']])) {
                $exception = $exceptionLUT[$decoded['status']];
            } else {
                $exception = GeneralException::class;
            }

            if($exception === AuthException::class) {
                throw new $exception(
                    'We were unable to authenticate your request. '
                    . 'The following information was supplied: '
                    . "{$decoded['message']}"
                    . "\n\n(auth_failure)"
                );
            } else {
                throw new $exception(
                    'We were unable to complete your request. '
                    . 'The following information was supplied: '
                    . "{$decoded['message']}"
                    . "\n\n({$decoded['status']})"
                );
            }
        }

        return $this->decodedResponse = $decoded;
    }
}
