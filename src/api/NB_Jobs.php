<?php namespace NeverBounce\API;
/**
 * Class NB_Job
 *
 * @package NeverBounce\API
 */
class NB_Jobs {
    use NB_App;

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
	 * Get list of jobs
	 */
	public function __construct() {
		$this->getList();
	}

	/**
	 * Paginates results
	 *
	 * @param int $offset
	 * @param int $perpage
	 *
	 * @return $this
	 */
	public function get($offset = 0, $perpage = 10) {
		$this->retrieve(array_splice($this->jobList, $offset, $perpage));

		return $this;
	}

	/**
	 * Gets first job
	 * @return $this
	 */
	public function first() {
		$this->retrieve($this->jobList[0]);

		return $this;
	}

	/**
	 * Retrieves status for job(s)
	 *
	 * @param int|array $jobs
	 *
	 * @return $this
	 */
	public function retrieve( $jobs ) {
		if(is_array($jobs)) {
			foreach ($jobs as $job) {
				$this->request( 'status', [ 'job_id' => $job ] );
				array_push($this->jobs, new NB_Job($job, $this->response));
			}
		}else{
			$this->request( 'status', [ 'job_id' => $jobs ] );
			array_push($this->jobs, new NB_Job($jobs, $this->response));
		}

		return $this;
	}

	/**
	 * Reverses the list
	 * @return $this
	 */
	public function desc() {
		rsort($this->jobList);

		return $this;
	}

	/**
	 * Returns jobs
	 * @return array
	 */
	public function jobs() {
		return $this->jobs;
	}

	/**
	 * Get list of all jobs
	 *
	 * @return $this
	 */
	private function getList() {
		$this->request( 'list_jobs' );

		foreach($this->response->jobs as $job) {
			array_push($this->jobList, (integer) $job);
		}
	}
}