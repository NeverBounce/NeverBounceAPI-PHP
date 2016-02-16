<?php namespace NeverBounce;

use NeverBounce\Error\Api;
use NeverBounce\HttpClient\ClientInterface;

class ApiClient {

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * ApiClient constructor.
     * @param ClientInterface $clientInterface
     */
    public function __construct(ClientInterface $clientInterface)
    {
        $this->client = $clientInterface;
    }

    /**
     * @param $endpoint
     * @param array $params
     * @return mixed
     * @throws Api
     */
    public function request($endpoint, array $params = [])
    {
        $url = Auth::getUrl() . $endpoint;
        $params['access_token'] = OAuthClient::getAccessToken();
        $res = $this->client->request($url, $params);
        return $this->response($res);
    }

    /**
     * Public only for testing... should not be called directly
     * @param $response
     * @return mixed
     * @throws Api
     */
    public function response($response)
    {
        $decoded = json_decode($response[0], true);
        if($decoded === null) {
            throw new Api(
                "The response from NeverBounce was unable "
                . "to be parsed as json. Try the request "
                . "again, if this error persists"
                . " let us know at support@neverbounce.com."
                . "\n\n(Internal error [status $response[1]: $response[0])"
            );
        }

        if(isset($decoded['msg'])) {
            throw new Api(
                "We were unable to complete your request. "
                . "The following information was supplied: "
                . "{$decoded['msg']}"
                . "\n\n(Request error)"
            );
        }

        return $decoded;
    }

}