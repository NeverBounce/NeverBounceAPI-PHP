<?php namespace NeverBounce;

use NeverBounce\Object\SingleVerification;

class SingleVerificationTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleVerification()
    {
        $verify = new SingleVerification('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('valid', $verify->result);
        $this->assertEquals(0, $verify->result_integer);

        $verify = new SingleVerification('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('invalid', $verify->result);
        $this->assertEquals(1, $verify->result_integer);

        $verify = new SingleVerification('disposable@neverbounce.com', [
            'result' => 'disposable',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('disposable', $verify->result);
        $this->assertEquals(2, $verify->result_integer);

        $verify = new SingleVerification('catchall@neverbounce.com', [
            'result' => 'catchall',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('catchall', $verify->result);
        $this->assertEquals(3, $verify->result_integer);

        $verify = new SingleVerification('unknown@neverbounce.com', [
            'result' => 'unknown',
            'flags' => [],
            'suggested_correction' => '',
        ]);
        $this->assertEquals('unknown', $verify->result);
        $this->assertEquals(4, $verify->result_integer);
    }

    public function testFlags()
    {
        $verification = new SingleVerification('valid@neverbounce.com', [
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
        $verification = new SingleVerification('valid@gmal.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => 'valid@gmail.com',
        ]);

        $this->assertEquals('valid@gmail.com',
            $verification->suggested_correction);
    }

    public function testIs()
    {
        $valid = new SingleVerification('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue(true, $valid->is(SingleVerification::VALID));
        $this->assertTrue(true, $valid->is('valid'));
        $this->assertTrue(true, $valid->is(0));
        $this->assertFalse($valid->is(SingleVerification::INVALID));
        $this->assertFalse($valid->is(SingleVerification::DISPOSABLE));
        $this->assertFalse($valid->is(SingleVerification::CATCHALL));
        $this->assertFalse($valid->is(SingleVerification::UNKNOWN));

        $this->assertTrue(true, $valid->is([SingleVerification::VALID]));
        $this->assertFalse($valid->is([
            SingleVerification::INVALID,
            SingleVerification::CATCHALL,
            SingleVerification::UNKNOWN,
            SingleVerification::DISPOSABLE,
        ]));
    }

    public function testNot()
    {
        $valid = new SingleVerification('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue(true, $valid->not(SingleVerification::INVALID));
        $this->assertTrue(true, $valid->not(1));
        $this->assertTrue(true, $valid->not('invalid'));
        $this->assertTrue(true, $valid->not(SingleVerification::DISPOSABLE));
        $this->assertTrue(true, $valid->not(SingleVerification::CATCHALL));
        $this->assertTrue(true, $valid->not(SingleVerification::UNKNOWN));
        $this->assertFalse($valid->not(SingleVerification::VALID));

        $this->assertTrue(true, $valid->not([
            SingleVerification::INVALID,
            SingleVerification::CATCHALL,
            SingleVerification::UNKNOWN,
            SingleVerification::DISPOSABLE,
        ]));
        $this->assertFalse($valid->not([SingleVerification::VALID]));
    }
}