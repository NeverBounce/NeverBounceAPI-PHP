<?php namespace NeverBounce;

class AuthTest extends TestCase
{
    public function testApiKeySetterGetter()
    {
        Auth::setApiKey(null);
        $this->assertNull(Auth::getApiKey());

        Auth::setApiKey('abc');
        $this->assertEquals('abc', Auth::getApiKey());

        Auth::setApiKey('123');
        $this->assertEquals('123', Auth::getApiKey());
    }
}