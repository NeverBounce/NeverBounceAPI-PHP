<?php namespace NeverBounce;

use NeverBounce\Object\ListVerification;

class Lists extends ApiClient {

    /**
     * @return array
     */
    public static function all()
    {
        $api = new self();
        $res = $api->request('list_user_jobs');
        $lists = [];
        foreach($res as $list) {
            $list[] = new ListVerification($list);
        }
        return $lists;
    }

    /**
     * @param $id
     * @return ListVerification
     */
    public static function get($id)
    {
        $api = new self();
        $res = $api->request('status', ['version' => '3.1', 'job_id' => $id]);
        return new ListVerification($res);
    }
}