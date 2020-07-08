<?php namespace NeverBounce;

use NeverBounce\Object\ResponseObject;
use NeverBounce\Object\VerificationObject;

class Jobs extends ApiClient
{
    const REMOTE_INPUT = 'remote_url';
    const SUPPLIED_INPUT = 'supplied';

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
     * @param string $jobId
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function status($jobId)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('GET', 'jobs/status', ['job_id' => $jobId]);
        return new ResponseObject($res);
    }

    /**
     * @param string $input
     * @param string $inputlocation
     * @param bool   $runsample
     * @param bool   $autoparse
     * @param bool   $autostart
     * @param bool|null $historicalData
     * @param bool   $allowManualReview
     * @param string $callbackUrl
     * @param array  $callbackHeaders
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function create(
        $input,
        $inputlocation,
        $filename,
        $runsample = null,
        $autoparse = null,
        $autostart = null,
        $historicalData = null,
        $allowManualReview = null,
        $callbackUrl = null,
        $callbackHeaders = null
    ) {
        self::$lastInstance = $obj = new self();
        $obj->setContentType('application/json');
        $params = [
            'input_location' => $inputlocation,
            'input' => $input,
            'filename' => $filename,
            'run_sample' => $runsample,
            'auto_start' => $autostart,
            'auto_parse' => $autoparse,
            'allow_manual_review' => $allowManualReview,
        ];

        if ($callbackUrl) {
            $params['callback_url'] = $callbackUrl;
        }

        if ($callbackHeaders && is_array($callbackHeaders)) {
            $params['callback_headers'] = $callbackHeaders;
        }

        if ($historicalData !== null) {
            $params['request_meta_data'] = ['leverage_historical_data' => $historicalData ? 1 : 0];
        }

        $res = $obj->request('POST', 'jobs/create', $params);
        return new ResponseObject($res);
    }

    /**
     * @param string $jobId
     * @param bool $autostart
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function parse($jobId, $autostart = null)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'jobs/parse', [
            'job_id' => $jobId,
            'auto_start' => $autostart,
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param string $jobId
     * @param bool   $runsample
     * @param bool $allowManualReview
     * @return ResponseObject
     * @throws \NeverBounce\Errors\ThrottleException
     * @throws \NeverBounce\Errors\HttpClientException
     * @throws \NeverBounce\Errors\GeneralException
     * @throws \NeverBounce\Errors\BadReferrerException
     * @throws \NeverBounce\Errors\AuthException
     */
    public static function start($jobId, $runsample = null, $allowManualReview = null)
    {
        self::$lastInstance = $obj = new self();
        $res = $obj->request('POST', 'jobs/start', [
            'job_id' => $jobId,
            'run_sample' => $runsample,
            'allow_manual_review' => $allowManualReview
        ]);
        return new ResponseObject($res);
    }

    /**
     * @param string $jobId
     * @param array  $query
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
        $res = $obj->setAcceptedType('application/octet-stream')
            ->request('GET', 'jobs/download', array_merge($query, [
                'job_id' => $jobId
            ]));
        return $res;
    }

    /**
     * @param string $jobId
     * @param array  $query
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
        foreach ($res['results'] as $key => $value) {
            $res['results'][$key]['verification'] = new VerificationObject(
                $value['data']['email'],
                $value['verification']
            );
        }
        return new ResponseObject($res);
    }
}
