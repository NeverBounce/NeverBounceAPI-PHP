<?php namespace NeverBounce\API;

class NB_Bulk
{
    use NB_App;

    /**
     * Input Location
     *
     * Data can be retrieved from this URL
     */
    const REMOTE_URL = 0;

    /**
     * Input Location
     *
     * Data is passed along in a string
     */
    const SUPPLIED_DATA = 1;

    /**
     * Contains job information
     * @var array
     */
    protected $jobs = [];

    /**
     * Contains list of Job IDs
     * @var array
     */
    protected $jobList = [];

    /**
     * Should we get cache tokens?
     * @var bool
     */
    protected $cache = false;

    /**
     * Create a new job
     *
     * @param string $input Either a string containing csv data or a url to a csv
     * @param string $input_location Denotes whether $input is the data or a url to the data
     * @param string $filename The filename this job will run under, by default a generic name will be supplied
     * @return NB_Bulk
     */
    public function create($input, $input_location, $filename = null)
    {
        if (empty($filename))
            $filename = $this->generateFilename();

        $this->request('bulk', [
            'input' => $input,
            'input_location' => $input_location,
            'filename' => $filename,
        ]);

        if(isset($this->response->job_id))
            $this->retrieve($this->response->job_id);

        return $this;
    }

    /**
     * Creates a new job and starts sampling it
     * 
     * @param string $input Either a string containing csv data or a url to a csv
     * @param string $input_location Denotes whether $input is the data or a url to the data
     * @param string $filename The filename this job will run under, by default a generic name will be supplied
     * @return NB_Bulk
     */
    public function sample($input, $input_location, $filename = null)
    {
        if (empty($filename))
            $filename = $this->generateFilename();

        $this->request('bulk', [
            'input' => $input,
            'input_location' => $input_location,
            'filename' => $filename,
            'run_sample' => 1
        ]);

        if(isset($this->response->job_id))
            $this->retrieve($this->response->job_id);

        return $this;
    }

    /**
     * Starts a job waiting to be started (after a sample has been run)
     * @param  int $id The id of the job to start
     */
    public function start($id)
    {
        $this->request('start_job', [
            'job_id' => $id
        ]);
    }

    /**
     * Generate a new filename
     *
     * @return string
     */
    private function generateFilename()
    {
        return uniqid('NBApi_') . '.csv';
    }

    /**
     * Retrieves status for job(s)
     *
     * @param int|array $jobs Job ID(s)
     *
     * @return $this
     */
    public function retrieve($jobs)
    {
        $this->jobs = [];
        if (is_array($jobs)) {
            foreach ($jobs as $job) {
                $this->request('status', ['job_id' => $job]);
                array_push($this->jobs, new NB_Job($this->response));
            }
        } else {
            $this->request('status', ['job_id' => $jobs]);
            array_push($this->jobs, new NB_Job($this->response));
        }

        return $this;
    }

    /**
     * Get list of all jobs
     *
     * @return $this
     */
    public function get()
    {
        $this->jobs = [];

        $post = [];
        if ($this->cache)
            $post['build_sync_cache'] = 1;

        $this->request('list_user_jobs', $post);

        foreach ($this->response->jobs as $job) {
            array_push($this->jobs, new NB_Job($job));
        }

        return $this;
    }

    /**
     * Should we get cache tokens?
     * @param bool $arg
     * @return $this
     */
    public function withCache($arg = true)
    {
        $this->cache = ($arg) ? true : false;

        return $this;
    }

    /**
     * Downloads the job data
     *
     * @param array $options
     * @return string
     * @throws NB_Exception
     */
    public function download($job, $options = [])
    {
        $this->request('download', [
            'job_id' => $job,
            'valids' => (isset($options['valids'])) ? (integer) $options['valids'] : 1,
            'invalids' => (isset($options['invalids'])) ? (integer) $options['invalids'] : 1,
            'catchall' => (isset($options['catchall'])) ? (integer) $options['catchall'] : 1,
            'disposable' => (isset($options['disposable'])) ? (integer) $options['disposable'] : 1,
            'unknown' => (isset($options['unknown'])) ? (integer) $options['unknown'] : 1,
            'duplicates' => (isset($options['duplicates'])) ? (integer) $options['duplicates'] : 1,
            'textcodes' => (isset($options['textcodes'])) ? (integer) $options['textcodes'] : 1,
        ], false);

        return $this->response_raw;
    }

    /**
     * Deletes the job data
     *
     * @throws NB_Exception
     */
    public function delete($job)
    {
        $this->request('delete_user_job', [
            'job_id' => $job
        ]);
    }

    /**
     * Selects a single job from the array
     * returns false, if item doesn't exist
     *
     * @param $id Job ID
     * @return bool|NB_Job
     */
    public function select($id)
    {
        foreach ($this->jobs as $key => $value) {
            if ($value->id === $id)
                return $value;
        }

        return false;
    }

    /**
     * Gets first job
     * @return $this
     */
    public function first()
    {
        return $this->jobs[0];
    }

    /**
     * Reverses the list
     * @return $this
     */
    public function desc()
    {
        rsort($this->jobs);

        return $this;
    }

    /**
     * Returns jobs
     * @return array
     */
    public function all()
    {
        return $this->jobs;
    }
}
