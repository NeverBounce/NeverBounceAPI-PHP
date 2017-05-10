<?php namespace NeverBounce;

use NeverBounce\Object\ResponseObject;

class Jobs extends ApiClient
{
    /**
     * @param array $query
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function search($query = [])
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'jobs/search', $query);
        foreach ($res['results'] as $key => $value) {
            $res['results'][$key] = new ResponseObject($value);
        }
        return new ResponseObject($res);
    }

    /**
     * @param $id
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function status($id)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'jobs/status', ['job_id' => $id]);
        return new ResponseObject($res);
    }

    /**
     * @param $url
     * @param bool $autoparse
     * @param bool $autorun
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function createFromUrl($url, $filename, $autoparse = true, $autorun = true)
    {
        self::$lastInstance = $obj = new self();
        $obj->setContentType('application/json');
        $res = $obj->request('POST', 'jobs/create', [
            'input_location' => 'remote_url',
            'input' => $url,
            'filename' => $filename,
            'auto_run' => (integer) $autorun,
            'auto_parse' => (integer) $autoparse,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $json
     * @param $filename
     * @param bool $autoparse
     * @param bool $autorun
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function createFromJson($json, $filename, $autoparse = true, $autorun = true)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'jobs/create', [
            'input_location' => 'json',
            'input' => $json,
            'filename' => $filename,
            'auto_run' => (integer) $autorun,
            'auto_parse' => (integer) $autoparse,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $string
     * @param $filename
     * @param bool $autoparse
     * @param bool $autorun
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function createFromString($string, $filename, $autoparse = true, $autorun = true)
    {
        self::$lastInstance = $obj = new self();
        $obj->setContentType('application/json');
        $res = $obj->request('POST', 'jobs/create', [
            'input_location' => 'supplied',
            'input' => $string,
            'filename' => $filename,
            'auto_run' => (integer) $autorun,
            'auto_parse' => (integer) $autoparse,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $jobId
     * @param bool $autostart
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function parse($jobId, $autostart = false)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'jobs/parse', [
            'job_id' => $jobId,
            'auto_start' => $autostart,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $jobId
     * @param bool $runsample
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function start($jobId, $runsample = false)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'jobs/parse', [
            'job_id' => $jobId,
            'run_sample' => $runsample,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $jobId
     * @param array $query
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function download($jobId, $query = [])
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GEET', 'jobs/download', array_merge($query, [
            'job_id' => $jobId
        ]));
        return new ResponseObject($res);
    }
}