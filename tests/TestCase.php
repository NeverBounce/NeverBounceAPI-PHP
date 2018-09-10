<?php namespace NeverBounce;

use NeverBounce\HttpClient\HttpClientInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function generateHeaders()
    {
        return [
            "Access-Control-Allow-Origin" => "*",
            "Cache-Control" => "no-store",
            "Connection" => "keep-alive",
            "Content-Length" => "120",
            "Content-Type" => "application/json",
            "Date" => "Tue, 16 Feb 2016 04:05:43 GMT",
            "Pragma" => "no-cache",
            "Server" => "nginx/1.4.6 (Ubuntu)",
            "X-Powered-By" => "HHVM/3.11.0",
        ];

    }

    protected function getMockHttpClient()
    {
        $mock = $this->getMockBuilder(HttpClientInterface::class);
        $mock->setMethods([
            'init',
            'setOpt',
            'execute',
            'getInfo',
            'getErrno',
            'getError',
            'close',
        ]);
        return $mock->getMock();
    }
}