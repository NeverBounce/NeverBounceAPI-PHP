<?php namespace NeverBounce;

use NeverBounce\Object\SingleVerification;

class Single extends ApiClient
{
    /**
     * @param $email
     * @return SingleVerification
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function verify($email)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'single/check', [
            'email' => $email,
            'credits_info' => true,
        ]);
        return new SingleVerification($email, $res);
    }

    /**
     * @param $email
     * @return SingleVerification
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function verifyWithAddressInformation($email)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'single/check', [
            'email' => $email,
            'credits_info' => true,
            'address_info' => true,
        ]);
        return new SingleVerification($email, $res);
    }
}