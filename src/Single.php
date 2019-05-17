<?php namespace NeverBounce;

use NeverBounce\Object\VerificationObject;

class Single extends ApiClient
{
    /**
     * @param string    $email
     * @param bool|null $addressinfo
     * @param bool|null $creditsinfo
     * @param int|null  $maxexecution
     * @return VerificationObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function check($email, $addressinfo = null, $creditsinfo = null, $maxexecution = null)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'single/check', [
            'email'              => $email,
            'address_info'       => $addressinfo,
            'credits_info'       => $creditsinfo,
            'max_execution_time' => $maxexecution,
        ]);
        return new VerificationObject($email, $res);
    }
}
