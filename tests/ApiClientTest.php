<?php namespace NeverBounce;

class ApiClientTest extends TestCase {

    public function testSuccessfulRequest()
    {
        $json = '{ "success": true, "result": 0, "result_details": 0, "execution_time": 0.32740187644958 }';
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);

        $client = new ApiClient($mock);
        $this->assertEquals(json_decode($json, true), $client->response([$json, 200, $this->generateHeaders()]));
    }

    public function testBadRequest()
    {
        $json = '{ "success": false, "msg": "Authentication failed" }';
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);

        $client = new ApiClient($mock);
        $this->setExpectedException('NeverBounce\Error\Api',
            "We were unable to complete your request. "
            . "The following information was supplied: "
            . "Authentication failed"
            . "\n\n(Request error)"
        );

        $client->response([$json, 200, $this->generateHeaders()]);
    }

    public function testBadResponse()
    {
        $json = "NOT JSON!!";
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);

        $client = new ApiClient($mock);
        $this->setExpectedException('NeverBounce\Error\Api',
            "The response from NeverBounce was unable "
            . "to be parsed as json. Try the request "
            . "again, if this error persists"
            . " let us know at support@neverbounce.com."
            . "\n\n(Internal error [status 200: NOT JSON!!)"
        );

        $client->response([$json, 200, $this->generateHeaders()]);
    }

}