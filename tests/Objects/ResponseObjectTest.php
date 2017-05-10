<?php

namespace NeverBounce;

use NeverBounce\Object\ResponseObject;

class ResponseObjectTest extends TestCase
{
    public function testGetter()
    {
        $resp = new ResponseObject([
            'key' => 'value',
        ]);
        $this->assertNotEmpty($resp->key);
        $this->assertEquals('value', $resp->key);
    }

    public function testSetter()
    {
        $resp = new ResponseObject([
            'key' => 'value',
        ]);

        // Setter should dispose of value and keep original value
        $resp->key = 'value2';
        $this->assertEquals('value', $resp->key);
    }
}