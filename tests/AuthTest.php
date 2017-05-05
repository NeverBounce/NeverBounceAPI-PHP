<?php namespace NeverBounce;

class AuthTest extends \PHPUnit_Framework_TestCase {

    public function testApiKeySetterGetter()
    {
        $this->assertNull(Auth::getApiKey());

        Auth::setApiKey('abc');
        $this->assertEquals('abc', Auth::getApiKey());

        Auth::setApiKey('123');
        $this->assertEquals('123', Auth::getApiKey());
    }
}