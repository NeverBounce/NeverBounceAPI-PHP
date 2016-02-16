<?php namespace NeverBounce;

use NeverBounce\Object\Verification;

class Single extends ApiClient {

    /**
     * @param $email
     * @return Verification
     */
    public static function verify($email)
    {
        $api = new self();
        $res = $api->request('single', ['email' => $email]);
        return new Verification($email, $res['result']);
    }

}