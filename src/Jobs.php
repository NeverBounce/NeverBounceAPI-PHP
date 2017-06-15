<?php namespace NeverBounce;

use NeverBounce\Object\ResponseObject;
use NeverBounce\Object\VerificationObject;

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
     * @param bool $runsample
     * @param bool $autoparse
     * @param bool $autostart
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function createFromUrl($url, $filename, $runsample = false, $autoparse = true, $autostart = true)
    {
        self::$lastInstance = $obj = new self();
        $obj->setContentType('application/json');
        $res = $obj->request('POST', 'jobs/create', [
            'input_location' => 'remote_url',
            'input' => $url,
            'filename' => $filename,
            'run_sample' => (integer) $runsample,
            'auto_start' => (integer) $autostart,
            'auto_parse' => (integer) $autoparse,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $array
     * @param $filename
     * @param bool $runsample
     * @param bool $autoparse
     * @param bool $autostart
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function createFromArray($array, $filename, $runsample = false, $autoparse = true, $autostart = true)
    {
        self::$lastInstance = $obj = new self();
        $obj->setContentType('application/json');
        $res = $obj->request('POST', 'jobs/create', [
            'input_location' => 'supplied',
            'input' => $array,
            'filename' => $filename,
            'run_sample' => (integer) $runsample,
            'auto_start' => (integer) $autostart,
            'auto_parse' => (integer) $autoparse,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $filehandler
     * @param $filename
     * @param bool $autoparse
     * @param bool $autostart
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
//    public static function createFromFile($filehandler, $filename, $autoparse = true, $autostart = true)
//    {
//        self::$lastInstance = $obj = new self();
//        $res = $obj->request('PUT', 'jobs/create', [], $filehandler);
//        return new ResponseObject($res);
//    }

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
        $res = $obj->request('POST', 'jobs/start', [
            'job_id' => $jobId,
            'run_sample' => $runsample,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param $jobId
     * @param array $query
     * @return string
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function download($jobId, $query = [])
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'jobs/download', array_merge($query, [
            'job_id' => $jobId
        ]));
        return $res;
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
    public static function results($jobId, $query = [])
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'jobs/results', array_merge($query, [
            'job_id' => $jobId
        ]));

        // Wrap verification results with teh VerificationObject
        foreach($res['results'] as $key => $value) {
            $res['results'][$key]['verification'] = new VerificationObject($value['data']['email'], $value['verification']);
        }
        return new ResponseObject($res);
    }
}