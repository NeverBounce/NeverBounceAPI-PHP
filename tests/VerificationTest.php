<?php namespace NeverBounce;

use NeverBounce\Object\Verification;

class VerificationTest extends \PHPUnit_Framework_TestCase {

    public function testTextCode()
    {
        $verify = new Verification('valid@neverbounce.com', 0);
        $this->assertEquals('valid', $verify->getTextCode());

        $verify = new Verification('invalid@neverbounce.com', 1);
        $this->assertEquals('invalid', $verify->getTextCode());

        $verify = new Verification('disposable@neverbounce.com', 2);
        $this->assertEquals('disposable', $verify->getTextCode());

        $verify = new Verification('catchall@neverbounce.com', 3);
        $this->assertEquals('catchall', $verify->getTextCode());

        $verify = new Verification('unknown@neverbounce.com', 4);
        $this->assertEquals('unknown', $verify->getTextCode());
    }

    public function testIs()
    {
        $valid = new Verification('valid@neverbounce.com', 0);
        $this->assertTrue(true, $valid->is(Verification::VALID));
        $this->assertFalse($valid->is(Verification::INVALID));
        $this->assertFalse($valid->is(Verification::DISPOSABLE));
        $this->assertFalse($valid->is(Verification::CATCHALL));
        $this->assertFalse($valid->is(Verification::UNKNOWN));

        $this->assertTrue(true, $valid->is([Verification::VALID]));
        $this->assertFalse($valid->is([Verification::INVALID, Verification::CATCHALL, Verification::UNKNOWN, Verification::DISPOSABLE]));
    }

    public function testNot()
    {
        $valid = new Verification('valid@neverbounce.com', 0);
        $this->assertTrue(true, $valid->not(Verification::INVALID));
        $this->assertTrue(true, $valid->not(Verification::DISPOSABLE));
        $this->assertTrue(true, $valid->not(Verification::CATCHALL));
        $this->assertTrue(true, $valid->not(Verification::UNKNOWN));
        $this->assertFalse($valid->not(Verification::VALID));

        $this->assertTrue(true, $valid->not([Verification::INVALID, Verification::CATCHALL, Verification::UNKNOWN, Verification::DISPOSABLE]));
        $this->assertFalse($valid->not([Verification::VALID]));
    }

}