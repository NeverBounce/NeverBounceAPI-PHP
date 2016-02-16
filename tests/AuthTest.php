<?php namespace NeverBounce;

class AuthTest extends \PHPUnit_Framework_TestCase {

    public function testGetUrl()
    {
        $this->assertEquals('https://api.neverbounce.com/v3/', Auth::getUrl());

        Auth::setVersion('v4');
        $this->assertEquals('https://api.neverbounce.com/v4/', Auth::getUrl());

        Auth::setRouter('api2');
        $this->assertEquals('https://api2.neverbounce.com/v4/', Auth::getUrl());

        Auth::setRouter(Auth::DEFAULT_ROUTER);
        Auth::setVersion(Auth::DEFAULT_VERSION);
        $this->assertEquals('https://api.neverbounce.com/v3/', Auth::getUrl());
    }

}