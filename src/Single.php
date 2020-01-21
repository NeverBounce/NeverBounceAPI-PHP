<?php namespace NeverBounce;

use NeverBounce\Object\VerificationObject;

class Single extends ApiClient
{
    /**
     * @param string    $email
     * @param bool|null $addressinfo
     * @param bool|null $creditsinfo
     * @param int|null  $timeout
     * @param bool|null $historicalData
     * @return VerificationObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function check(
        $email,
        $addressinfo = null,
        $creditsinfo = null,
        $timeout = null,
        $historicalData = null
    ) {
        self::$lastInstance = $obj = new self();
        $params = [
            'email' => $email,
            'address_info' => $addressinfo,
            'credits_info' => $creditsinfo,
            'timeout' => $timeout,
        ];

        if ($historicalData !== null) {
            $params['request_meta_data'] = ['leverage_historical_data' => $historicalData ? 1 : 0];
        }

        $res = $obj->request('GET', 'single/check', $params);
        return new VerificationObject($email, $res);
    }
}
