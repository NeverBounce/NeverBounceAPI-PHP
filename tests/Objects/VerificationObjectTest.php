<?php namespace NeverBounce;

use NeverBounce\Object\VerificationObject;

class VerificationObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleVerification()
    {
        $verify = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('valid', $verify->result);
        $this->assertEquals(0, $verify->result_integer);

        $verify = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('invalid', $verify->result);
        $this->assertEquals(1, $verify->result_integer);

        $verify = new VerificationObject('disposable@neverbounce.com', [
            'result' => 'disposable',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('disposable', $verify->result);
        $this->assertEquals(2, $verify->result_integer);

        $verify = new VerificationObject('catchall@neverbounce.com', [
            'result' => 'catchall',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('catchall', $verify->result);
        $this->assertEquals(3, $verify->result_integer);

        $verify = new VerificationObject('unknown@neverbounce.com', [
            'result' => 'unknown',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('unknown', $verify->result);
        $this->assertEquals(4, $verify->result_integer);
    }

    public function testFlags()
    {
        $verification = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => ['bad_syntax'],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($verification->hasFlag('bad_syntax'));
        $this->assertFalse($verification->hasFlag('has_dns'));
        $this->assertEquals(['bad_syntax'], $verification->flags);
    }

    public function testSuggestedCorrection()
    {
        $verification = new VerificationObject('valid@gmal.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => 'valid@gmail.com',
        ]);

        $this->assertEquals('valid@gmail.com',
            $verification->suggested_correction);
    }

    public function testIs()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue(true, $valid->is(VerificationObject::VALID));
        $this->assertTrue(true, $valid->is('valid'));
        $this->assertTrue(true, $valid->is(0));
        $this->assertFalse($valid->is(VerificationObject::INVALID));
        $this->assertFalse($valid->is(VerificationObject::DISPOSABLE));
        $this->assertFalse($valid->is(VerificationObject::CATCHALL));
        $this->assertFalse($valid->is(VerificationObject::UNKNOWN));

        $this->assertTrue(true, $valid->is([VerificationObject::VALID]));
        $this->assertFalse($valid->is([
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::UNKNOWN,
            VerificationObject::DISPOSABLE,
        ]));
    }

    public function testNot()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue(true, $valid->not(VerificationObject::INVALID));
        $this->assertTrue(true, $valid->not(1));
        $this->assertTrue(true, $valid->not('invalid'));
        $this->assertTrue(true, $valid->not(VerificationObject::DISPOSABLE));
        $this->assertTrue(true, $valid->not(VerificationObject::CATCHALL));
        $this->assertTrue(true, $valid->not(VerificationObject::UNKNOWN));
        $this->assertFalse($valid->not(VerificationObject::VALID));

        $this->assertTrue(true, $valid->not([
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::UNKNOWN,
            VerificationObject::DISPOSABLE,
        ]));
        $this->assertFalse($valid->not([VerificationObject::VALID]));
    }
}