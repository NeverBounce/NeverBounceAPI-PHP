<?php namespace NeverBounce;

use NeverBounce\HttpClient\HttpClientInterface;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function generateHeaders()
    {
        return [
            "content-type" => "application/json",
        ];

    }

    protected function getMockHttpClient()
    {
        return $this->getMock(HttpClientInterface::class,
            [
                'init',
                'setOpt',
                'execute',
                'getInfo',
                'getErrno',
                'getError',
                'close',
            ]);
    }
}