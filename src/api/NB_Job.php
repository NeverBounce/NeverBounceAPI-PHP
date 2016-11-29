<?php namespace NeverBounce\API;

class NB_Job {

    /**
     * Job is uploading
     */
    const JOB_UPLOADING = -1;

    /**
     * Job is ready
     */
    const JOB_READY = 0;

    /**
     * Job is indexing
     */
    const JOB_INDEXING = 1;

    /**
     * Job is indexed (awaiting payment)
     */
    const JOB_INDEXED = 2;

    /**
     * Job is running
     */
    const JOB_RUNNING = 3;

    /**
     * Job is complete
     */
    const JOB_COMPLETE = 4;

    /**
     * Job has failed
     */
    const JOB_FAILED = 5;

    /**
     * User readable definitions for statuses
     * @var array
     */
    protected static $definitions = [
        self::JOB_UPLOADING => 'Uploading',
        self::JOB_READY => 'Ready',
        self::JOB_INDEXING => 'Indexing',
        self::JOB_INDEXED => 'Purchase',
        self::JOB_RUNNING => 'Validating',
        self::JOB_COMPLETE => 'Completed',
        self::JOB_FAILED => 'Failed',
    ];

    /**
     * @param $job
	 * @param bool $sync
     */
    public function __construct($job, $sync = false)
    {
        $this->id = (integer)$job->id;

        if (property_exists($job, 'cache_token'))
            $this->cache_token = (string)$job->cache_token;

        $this->type = (integer)$job->type;
        $this->input_location = (integer)$job->input_location;
        $this->started = (integer)strtotime($job->created);

        //$stats = json_decode($job->stats);
        $this->filename = (string)$job->orig_name;
        $this->finished = (bool)$job->finished;
        $this->state = (integer)$job->status;
        $this->total = (integer)$job->stats->total;
        $this->processed = (integer)$job->stats->processed;
        $this->valid = (integer)$job->stats->valid;
        $this->invalid = (integer)$job->stats->invalid;
        $this->catchall = (integer)$job->stats->catchall;
        $this->disposable = (integer)$job->stats->disposable;
        $this->unknown = (integer)$job->stats->unknown;

        return $this;
    }

    /**
     * @param string $of
     *
     * @param int $accuracy
     *
     * @return float
     */
    public function percentage($of = 'total', $accuracy = 2)
    {

        if ($this->state < self::JOB_RUNNING || $this->state > self::JOB_COMPLETE || !isset($this->$of) || !is_numeric($this->$of))
            return null;

        $total = $this->total;
        // Don't divide by zero
        if ($total === 0)
            return 0;

        // If $of doesn't exist or is empty don't bother
        if (empty($this->$of))
            return 0;

        return (float)number_format(($this->$of / $total) * 100, $accuracy);
    }

    /**
     * @param string $of
     * @return int|null
     */
    public function total($of = 'total')
    {

        if ($this->state < self::JOB_RUNNING || $this->state > self::JOB_COMPLETE || !isset($this->$of) || !is_numeric($this->$of))
            return null;

        if (empty($this->$of))
            return 0;

        return (int)$this->$of;
    }

    /**
     * Downloads the job data
     *
     * @param array $options
     * @return string
     * @throws NB_Exception
     */
    public function download($options = [])
    {
        return NB_Bulk::app()->download($this->id, $options);
    }

    /**
     * Deletes the job data
     *
     * @throws NB_Exception
     */
    public function delete()
    {
        NB_Bulk::app()->delete($this->id);
    }
}
