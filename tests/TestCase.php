<?php namespace NeverBounce;

use NeverBounce\HttpClient\HttpClientInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function generateHeaders()
    {
        return [
            "content-type" => "application/json",
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