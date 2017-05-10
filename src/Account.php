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
        $obj = self::getInstance();
        $resp = $obj->request('GET', 'account/info');
        return new ResponseObject($resp);
    }
}