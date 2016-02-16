<?php namespace NeverBounce;

class OAuthClientTest extends TestCase {

    public function testEmptyGetter()
    {
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);
        $mock->expects($this->once())->method('request')->willReturn([
            '{"access_token":"2833cf94045e3d54938bca56e14ab686f475a06c","expires_in":3600,"token_type":"Bearer","scope":"basic user"}',
            200,
            $this->generateHeaders()
        ]);
        $this->assertEquals("2833cf94045e3d54938bca56e14ab686f475a06c", OAuthClient::getAccessToken($mock));
    }

    public function testSuccessfulRequest()
    {
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);
        $mock->expects($this->once())->method('request')->willReturn([
            '{"access_token":"2833cf94045e3d54938bca56e14ab686f475a06c","expires_in":3600,"token_type":"Bearer","scope":"basic user"}',
            200,
            $this->generateHeaders()
        ]);
        $this->assertEquals("2833cf94045e3d54938bca56e14ab686f475a06c", OAuthClient::request($mock));
    }

    public function testBadRequest()
    {
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);
        $mock->expects($this->once())->method('request')->willReturn([
            '{"error":"invalid_request","error_description":"The grant type was not specified in the request"}',
            400,
            $this->generateHeaders()
        ]);

        $this->setExpectedException('NeverBounce\Error\Api',
            "We were unable to complete your request. "
            . "The following information was supplied: "
            . "The grant type was not specified in the request"
            . "\n\n(Request error [invalid_request])"
        );

        OAuthClient::request($mock);
    }

    public function testBadResponse()
    {
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);
        $mock->expects($this->once())->method('request')->willReturn([
            'Not JSON!',
            400,
            $this->generateHeaders()
        ]);

        $this->setExpectedException('NeverBounce\Error\Api',
            "The response from NeverBounce was unable "
            . "to be parsed as json. Try the request "
            . "again, if this error persists"
            . " let us know at support@neverbounce.com."
            . "\n\n(Internal error)"
        );

        OAuthClient::request($mock);
    }

    public function testGetter()
    {
        $mock = $this->getMock('NeverBounce\HttpClient\ClientInterface', ['request']);
        $mock->expects($this->never())->method('request');
        $this->assertEquals("2833cf94045e3d54938bca56e14ab686f475a06c", OAuthClient::getAccessToken($mock));
    }

}