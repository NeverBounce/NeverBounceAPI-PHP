<?php

namespace NeverBounce;

use NeverBounce\Object\ResponseObject;

class Account extends ApiClient
{
    /**
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function info()
    {
        self::$lastInstance = $obj = new self();
        $resp = $obj->request('GET', 'account/info');
        return new ResponseObject($resp);
    }
}
