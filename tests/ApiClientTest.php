<?php namespace NeverBounce;

use NeverBounce\Errors\AuthException;
use NeverBounce\Errors\BadReferrerException;
use NeverBounce\Errors\GeneralException;
use NeverBounce\Errors\HttpClientException;
use NeverBounce\Errors\ThrottleException;

class ApiClientTest extends TestCase
{
    public function testConstructorWithoutApiKey()
    {
        $this->setExpectedException(AuthException::class);
        new ApiClient($this->getMockHttpClient());
    }

    public function testConstructorWithApiKey()
    {
        Auth::setApiKey('123');
        $api = new ApiClient($this->getMockHttpClient());
        $this->assertInstanceOf(ApiClient::class, $api);
    }

    public function testSetTimeout()
    {
        Auth::setApiKey('123');

        $reflector = new \ReflectionClass(ApiClient::class);
        $property = $reflector->getProperty('timeout');
        $property->setAccessible(true);

        $api = new ApiClient($this->getMockHttpClient());
        $api->setTimeout(60);
        $this->assertEquals(60, $property->getValue($api));

        $api->setTimeout('60');
        $this->assertEquals(60, $property->getValue($api));

        $api->setTimeout(-1);
        $this->assertEquals(0, $property->getValue($api));
    }

    public function testSetContentType()
    {
        Auth::setApiKey('123');

        $reflector = new \ReflectionClass(ApiClient::class);
        $property = $reflector->getProperty('contentType');
        $property->setAccessible(true);

        $api = new ApiClient($this->getMockHttpClient());
        $this->assertEquals('application/x-www-form-urlencoded',
            $property->getValue($api));

        $api->setContentType('application/json');
        $this->assertEquals('application/json', $property->getValue($api));

        $this->setExpectedException(HttpClientException::class);
        $api->setContentType('application/pdf');
    }

    public function testSuccessfulRequest()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn('{
              "status": "success",
              "billing_type": "default",
              "credits": 53479,
              "free_api_credits": 0,
              "monthly_api_usage": 376,
              "monthly_dashboard_usage": 0,
              "jobs_completed": 286,
              "jobs_under_review": 0,
              "jobs_queued": 0,
              "jobs_processing": 0,
              "execution_time": 1419
            }');
        $mock->method('getInfo')->willReturn(200);
        $reflector = new \ReflectionClass(ApiClient::class);
        $property = $reflector->getProperty('responseHeaders');
        $property->setAccessible(true);

        $client = new ApiClient($mock);
        $property->setValue($client, $this->generateHeaders());
        $res = $client->request('GET', '/account/info');

        $this->assertArrayHasKey('status', $res);
        $this->assertArrayHasKey('status', $client->getDecodedResponse());
        $this->assertEquals(200, $client->getStatusCode());
    }

    public function testFailedRequest()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn(false);
        $mock->method('getErrno')->willReturn(7);
        $mock->method('getError')->willReturn('Curl Error');

        $this->setExpectedException(HttpClientException::class);

        $client = new ApiClient($mock);
        $client->request('GET', '/account/info');
        $this->assertEmpty($client->getDecodedResponse());
    }

    public function testSuccessResponse()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "success","result": "valid","flags": ["has_dns","has_dns_mx"],"suggested_correction": "","retry_token": "","execution_time": 499}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->assertEquals(json_decode($json, true),
            $method->invoke($client, $json, $this->generateHeaders(), 200));
    }

    public function testGeneralFailure()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "general_failure","message": "Something went wrong","execution_time": 96}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(GeneralException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }

    public function testBadResponse()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = "NOT JSON!!";
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(GeneralException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }

    public function testAuthFailure()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "auth_failure","message": "Invalid API key \'adsfad\'","execution_time": 96}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(AuthException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }

    public function testTempUnavail()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "temp_unavail","message": "Unable to communicate with backend services","execution_time": 96}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(GeneralException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }

    public function testThrottleTriggered()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "throttle_triggered","message": "Too many requests in a short amount of time","execution_time": 96}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(ThrottleException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }

    public function testBadReferrer()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $method = $reflector->getMethod('response');
        $method->setAccessible(true);

        $json = '{"status": "bad_referrer","message": "The referrer this request originated from is not on the trusted list","execution_time": 96}';
        $client = new ApiClient($this->getMockHttpClient());
        $this->setExpectedException(BadReferrerException::class);
        $method->invoke($client, $json, $this->generateHeaders(), 200);
    }
}