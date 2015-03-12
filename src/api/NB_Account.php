<?php namespace NeverBounce\API;

/**
 * Class NB_Account
 *
 * @package NeverBounce\API
 */
class NB_Account
{
    use NB_App;

    /**
     * Gets account information
     *
     * @return $this
     */
    public function check()
    {
        $this->request('account');

        return $this;
    }

    /**
     * Returns account balance
     *
     * @return int
     */
    public function balance()
    {
        return (integer)$this->response->credits;
    }

    /**
     * Returns number of completed jobs
     *
     * @return int
     */
    public function job_completed()
    {
        return (integer)$this->response->jobs_completed;
    }

    /**
     * Returns number of jobs currently being processed
     *
     * @return int
     */
    public function jobs_processing()
    {
        return (integer)$this->response->jobs_processing;
    }
}