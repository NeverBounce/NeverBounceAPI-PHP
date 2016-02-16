<?php namespace NeverBounce\HttpClient;

use NeverBounce\Auth;

class CurlClient implements ClientInterface {

    const DEFAULT_TIMEOUT = 90;
    const DEFAULT_CONNECT_TIMEOUT = 30;
    private $timeout = self::DEFAULT_TIMEOUT;
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) max($timeout, 0);
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout
     * @return $this
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = (int) max($connectTimeout, 0);
        return $this;
    }

    /**
     * @param string $url
     * @param array $params
     * @param bool $auth
     * @return array
     * @throws Error\ApiConnection
     */
    public function request($url, array $params = [], $auth = false)
    {
        $curl = curl_init();
        $opts = [];

        // Create a callback to capture HTTP headers for the response
        $resHeaders = array();
        $headerCallback = function ($curl, $header_line) use (&$resHeaders) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($header_line, ":") === false) {
                return strlen($header_line);
            }
            list($key, $value) = explode(":", trim($header_line), 2);
            $resHeaders[trim($key)] = trim($value);
            return strlen($header_line);
        };

        // Set CURLOPTS
        $opts[CURLOPT_HTTPHEADER] = [
            "User-Agent" => "NeverBounce-PHPSdk/" . Utils::wrapperVersion()
        ];
        $opts[CURLOPT_HEADERFUNCTION] = $headerCallback;
        $opts[CURLOPT_CONNECTTIMEOUT] = $this->connectTimeout;
        $opts[CURLOPT_TIMEOUT] = $this->timeout;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_POST] = true;

        // Are there params?
        if(count($params) > 0) {
            // Encoded using http_build_query, this defaults to the x-www-form-urlencoded standard
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
        }

        if($auth === true) {
            $opts[CURLOPT_USERPWD] = Auth::getApiKey() . ":" . Auth::getApiSecret();
        }

        curl_setopt_array($curl, $opts);
        $resBody = curl_exec($curl);

        // Catches curl errors
        if ($resBody === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($url, $errno, $message);
        }

        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return [$resBody, $resCode, $resHeaders];
    }

    /**
     * @param string $url
     * @param number $errno
     * @param string $message
     * @throws Error\ApiConnection
     */
    private function handleCurlError($url, $errno, $message)
    {
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to NeverBounce ($url).  Please check your "
                    . "internet connection and try again.  If this problem persists, "
                    . "you should";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify NeverBounce's SSL certificate.  Please make sure "
                    . "that your network is not intercepting certificates.  "
                    . "(Try going to $url in your browser.)  "
                    . "If this problem persists,";
                break;
            default:
                $msg = "Unexpected error communicating with NeverBounce.  "
                    . "If this problem persists,";
        }
        $msg .= " let us know at support@neverbounce.com.";
        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new Error\ApiConnection($msg);
    }

}