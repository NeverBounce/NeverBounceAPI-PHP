<?php namespace NeverBounce;

use NeverBounce\Object\VerificationObject;

class Single extends ApiClient
{
    /**
     * @param $email
     * @param int $maxexecution
     * @return VerificationObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function verify($email, $maxexecution = 60)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'single/check', [
            'email' => $email,
            'max_execution_time' => $maxexecution,
            'credits_info' => true,
        ]);
        return new VerificationObject($email, $res);
    }

    /**
     * @param string $email
     * @param int $maxexecution
     * @return VerificationObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function verifyWithAddressInformation($email, $maxexecution = 60)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'single/check', [
            'email' => $email,
            'max_execution_time' => $maxexecution,
            'credits_info' => true,
            'address_info' => true,
        ]);
        return new VerificationObject($email, $res);
    }
}
