<?php namespace NeverBounce\Object;

class ListVerification {

    const VERIFICATION_STATUS_UPLOADING = -1;
    const VERIFICATION_STATUS_READY = 0;
    const VERIFICATION_STATUS_INDEXING = 1;
    const VERIFICATION_STATUS_INDEXED = 2;
    const VERIFICATION_STATUS_RUNNING = 3;
    const VERIFICATION_STATUS_COMPLETE = 4;
    const VERIFICATION_STATUS_FAILED = 5;

    const SAMPLE_STATUS_READY = 1;
    const SAMPLE_STATUS_RUNNING = 2;
    const SAMPLE_STATUS_COMPLETED = 3;

    /**
     * @var array
     */
    protected $data;

    /**
     * ListVerification constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $key
     * @return mixed|null
     */
    function getMeta($key)
    {
        if(isset($this->job_details[$key]))
            return $this->job_details[$key];

        return null;
    }

    /**
     * @return bool
     */
    function isRunning()
    {
        return ($this->status === self::VERIFICATION_STATUS_RUNNING) ? true : false;
    }

    /**
     * @return bool
     */
    function isCompleted()
    {
        return ($this->status === self::VERIFICATION_STATUS_COMPLETE) ? true : false;
    }

    /**
     * @return mixed|null
     */
    function getSampleStatus()
    {
        return $this->getMeta('sample_status');
    }

    /**
     * @return bool
     */
    function isSampleRunning()
    {
        return ($this->getSampleStatus() === self::SAMPLE_STATUS_RUNNING) ? true : false;
    }

    /**
     * @return bool
     */
    function isSampleCompleted()
    {
        return ($this->getSampleStatus() === self::SAMPLE_STATUS_COMPLETED) ? true : false;
    }

    /**
     * @return mixed|null
     */
    function getBounceEstimate()
    {
        return $this->getMeta('bounce_estimate');
    }

}