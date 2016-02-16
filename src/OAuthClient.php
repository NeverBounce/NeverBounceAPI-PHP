<?php namespace NeverBounce;

use NeverBounce\Error\Api;
use NeverBounce\HttpClient\ClientInterface;

class OAuthClient {

    protected static $accessToken;

    /**
     * @param ClientInterface $client
     * @return mixed
     * @throws Api
     */
    public static function getAccessToken(ClientInterface $client)
    {
        if(!empty(self::$accessToken))
            return self::$accessToken;

        return self::request($client);
    }

    /**
     * @param ClientInterface $client
     * @return mixed
     * @throws Api
     */
    public static function request(ClientInterface $client)
    {
        $url = Auth::getUrl() . 'access_token';
        $res = $client->request($url, [
            'grant_type' => 'client_credentials',
            'scope' => 'basic user'
        ], true);

        $decoded = json_decode($res[0], true);
        if($decoded === null) {
            throw new Api(
                "The response from NeverBounce was unable "
                . "to be parsed as json. Try the request "
                . "again, if this error persists"
                . " let us know at support@neverbounce.com."
                . "\n\n(Internal error)"
            );
        }

        if(isset($decoded['error'])) {
            throw new Api(
                "We were unable to complete your request. "
                . "The following information was supplied: "
                . "{$decoded['error_description']}"
                . "\n\n(Request error [{$decoded['error']}])"
            );
        }

        return self::$accessToken = $decoded['access_token'];
    }

}