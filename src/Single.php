<?php namespace NeverBounce;

use NeverBounce\Object\Verification;

class Single {

    public static function verify($email)
    {
        $client = new HttpClient\CurlClient();
        $api = new ApiClient($client);
        $res = $api->request('single', ['email' => $email]);
        return new Verification($email, $res['result']);
    }

}