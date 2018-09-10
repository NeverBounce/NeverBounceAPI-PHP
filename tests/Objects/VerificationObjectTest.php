<?php namespace NeverBounce;

use NeverBounce\Object\VerificationObject;

class VerificationObjectTest extends TestCase
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

    public function testIsWithSingleString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->is('valid'));
        $this->assertFalse($valid->is('invalid'));
        $this->assertFalse($valid->is('catchall'));
        $this->assertFalse($valid->is('disposable'));
        $this->assertFalse($valid->is('unknown'));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($invalid->is('valid'));
        $this->assertTrue($invalid->is('invalid'));
        $this->assertFalse($invalid->is('catchall'));
        $this->assertFalse($invalid->is('disposable'));
        $this->assertFalse($invalid->is('unknown'));
    }

    public function testIsWithArrayOfString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->is(['valid', 'invalid', 'catchall', 'unknown', 'disable']));
        $this->assertTrue($valid->is(['valid']));
        $this->assertFalse($valid->is(['invalid']));
        $this->assertFalse($valid->is(['catchall']));
        $this->assertFalse($valid->is(['disposable']));
        $this->assertFalse($valid->is(['unknown']));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->is(['valid', 'invalid', 'catchall', 'unknown', 'disable']));
        $this->assertFalse($invalid->is(['valid']));
        $this->assertTrue($invalid->is(['invalid']));
        $this->assertFalse($invalid->is(['catchall']));
        $this->assertFalse($invalid->is(['disposable']));
        $this->assertFalse($invalid->is(['unknown']));
    }

    public function testIsWithIntegerString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->is(VerificationObject::VALID));
        $this->assertFalse($valid->is(VerificationObject::INVALID));
        $this->assertFalse($valid->is(VerificationObject::CATCHALL));
        $this->assertFalse($valid->is(VerificationObject::CATCHALL));
        $this->assertFalse($valid->is(VerificationObject::CATCHALL));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($invalid->is(VerificationObject::VALID));
        $this->assertTrue($invalid->is(VerificationObject::INVALID));
        $this->assertFalse($invalid->is(VerificationObject::CATCHALL));
        $this->assertFalse($invalid->is(VerificationObject::CATCHALL));
        $this->assertFalse($invalid->is(VerificationObject::CATCHALL));
    }

    public function testIsWithArrayOfIntegers()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->is([
            VerificationObject::VALID,
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));
        $this->assertTrue($valid->is([VerificationObject::VALID]));
        $this->assertFalse($valid->is([VerificationObject::INVALID]));
        $this->assertFalse($valid->is([VerificationObject::CATCHALL]));
        $this->assertFalse($valid->is([VerificationObject::DISPOSABLE]));
        $this->assertFalse($valid->is([VerificationObject::UNKNOWN]));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->is([
            VerificationObject::VALID,
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));
        $this->assertFalse($invalid->is([VerificationObject::VALID]));
        $this->assertTrue($invalid->is([VerificationObject::INVALID]));
        $this->assertFalse($invalid->is([VerificationObject::CATCHALL]));
        $this->assertFalse($invalid->is([VerificationObject::DISPOSABLE]));
        $this->assertFalse($invalid->is([VerificationObject::UNKNOWN]));
    }

    public function testIsWithArrayOfStringsAndIntegers()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->is([
            'valid',
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->is([
            VerificationObject::VALID,
            'invalid',
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));
    }

    public function testNotWithSingleString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($valid->not('valid'));
        $this->assertTrue($valid->not('invalid'));
        $this->assertTrue($valid->not('catchall'));
        $this->assertTrue($valid->not('disposable'));
        $this->assertTrue($valid->not('unknown'));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->not('valid'));
        $this->assertFalse($invalid->not('invalid'));
        $this->assertTrue($invalid->not('catchall'));
        $this->assertTrue($invalid->not('disposable'));
        $this->assertTrue($invalid->not('unknown'));
    }

    public function testNotWithArrayOfString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($valid->not(['valid', 'invalid', 'catchall', 'unknown', 'disable']));
        $this->assertFalse($valid->not(['valid']));
        $this->assertTrue($valid->not(['invalid']));
        $this->assertTrue($valid->not(['catchall']));
        $this->assertTrue($valid->not(['disposable']));
        $this->assertTrue($valid->not(['unknown']));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($invalid->not(['valid', 'invalid', 'catchall', 'unknown', 'disable']));
        $this->assertTrue($invalid->not(['valid']));
        $this->assertFalse($invalid->not(['invalid']));
        $this->assertTrue($invalid->not(['catchall']));
        $this->assertTrue($invalid->not(['disposable']));
        $this->assertTrue($invalid->not(['unknown']));
    }

    public function testNotWithIntegerString()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($valid->not(VerificationObject::VALID));
        $this->assertTrue($valid->not(VerificationObject::INVALID));
        $this->assertTrue($valid->not(VerificationObject::CATCHALL));
        $this->assertTrue($valid->not(VerificationObject::CATCHALL));
        $this->assertTrue($valid->not(VerificationObject::CATCHALL));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->not(VerificationObject::VALID));
        $this->assertFalse($invalid->not(VerificationObject::INVALID));
        $this->assertTrue($invalid->not(VerificationObject::CATCHALL));
        $this->assertTrue($invalid->not(VerificationObject::CATCHALL));
        $this->assertTrue($invalid->not(VerificationObject::CATCHALL));
    }

    public function testNotWithArrayOfIntegers()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($valid->not([
            VerificationObject::VALID,
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));
        $this->assertFalse($valid->not([VerificationObject::VALID]));
        $this->assertTrue($valid->not([VerificationObject::INVALID]));
        $this->assertTrue($valid->not([VerificationObject::CATCHALL]));
        $this->assertTrue($valid->not([VerificationObject::DISPOSABLE]));
        $this->assertTrue($valid->not([VerificationObject::UNKNOWN]));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertFalse($invalid->not([
            VerificationObject::VALID,
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            VerificationObject::UNKNOWN,
        ]));
        $this->assertTrue($invalid->not([VerificationObject::VALID]));
        $this->assertFalse($invalid->not([VerificationObject::INVALID]));
        $this->assertTrue($invalid->not([VerificationObject::CATCHALL]));
        $this->assertTrue($invalid->not([VerificationObject::DISPOSABLE]));
        $this->assertTrue($invalid->not([VerificationObject::UNKNOWN]));
    }

    public function testNotWithArrayOfStringsAndIntegers()
    {
        $valid = new VerificationObject('valid@neverbounce.com', [
            'result' => 'valid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($valid->not([
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            'unknown'
        ]));

        $this->assertFalse($valid->not([
            'valid',
            VerificationObject::INVALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            'unknown'
        ]));

        $invalid = new VerificationObject('invalid@neverbounce.com', [
            'result' => 'invalid',
            'flags' => [],
            'suggested_correction' => '',
        ]);

        $this->assertTrue($invalid->not([
            VerificationObject::VALID,
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            'unknown'
        ]));

        $this->assertFalse($invalid->not([
            VerificationObject::VALID,
            'invalid',
            VerificationObject::CATCHALL,
            VerificationObject::DISPOSABLE,
            'unknown'
        ]));
    }
}