<?php namespace NeverBounce;

use NeverBounce\Object\ResponseObject;

class PoE extends ApiClient
{
    /**
     * @param string $email
     * @param mixed  $result
     * @param string $confirmationToken
     * @param string $transactionId
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function confirm($email, $result, $confirmationToken, $transactionId)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'poe/confirm', [
            'email'              => $email,
            'result'             => $result,
            'confirmation_token' => $confirmationToken,
            'transaction_id'     => $transactionId,
        ]);
        return new ResponseObject($res);
    }
}
