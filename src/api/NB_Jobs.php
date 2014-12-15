<?php namespace NeverBounce\API;
/**
 * Class NB_Job
 *
 * @package NeverBounce\API
 */
class NB_Jobs {
    use NB_App;

	/**
	 * Input supplied in a publicly accessible file
	 */
	const INPUT_REMOTE_URL = 0;

	/**
	 * Input supplied in a string
	 */
	const INPUT_SUPPLIED = 1;

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
	 * Input type definitions
	 * @var array
	 */
	protected $input = [
		'remote_url' => self::INPUT_REMOTE_URL,
	    'supplied' => self::INPUT_SUPPLIED,
	];

	/**
	 * Get list of jobs
	 */
	public function __construct() {
		//$this->getList();
	}

	/**
	 * Paginates results
	 *
	 * @param int $offset Where the cursor should start
	 * @param int $perpage How many results to display per page
	 *
	 * @return $this
	 */
	public function get($offset = 0, $perpage = 10) {
		$this->getList();
		$this->retrieve(array_splice($this->jobList, $offset, $perpage));

		return $this;
	}

	/**
	 * Retrieves status for job(s)
	 *
	 * @param int|array $jobs Job ID(s)
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
	 * Gets first job
	 * @return $this
	 */
	public function first() {
		$this->retrieve($this->jobList[0]);

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
	public function all() {
		return $this->jobs;
	}

	/**
	 * Selects a single job from the array
	 * returns false, if item doesn't exist
	 *
	 * @param $id Job ID
	 * @return bool|NB_Job
	 */
	public function single($id) {
		foreach($this->jobs as $key => $value) {
			if($value->id === $id)
				return $value;
		}

		return false;
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

	/**
	 * Starts new job
	 * @param int|string $location Location indicator of input data
	 * @param string $data Input data string or file location
	 * @param string $name Name of file
	 * @param string|null $callback Callback url for system to contact on completion
	 * @param array|null $params Additional parameters to pass with callback
	 *
	 * @return $this
	 * @throws \NeverBounce\API\NB_Exception
	 */
	public function create($location, $data, $name = null, $callback = null, $params = null) {
		if(is_numeric($location) && $location > 1) {
			throw new NB_Exception('Input location out of range, it should be either 0 for a remote file or 1 for a string');
		}
		else if(is_string($location) && !in_array($location, $this->input)) {
			throw new NB_Exception('Input location out of range, it should be either "remote_url" for a remote file or "supplied" for a string');
		}

		if($name === null) {
			$name = uniqid("NBApi_");
		}

		$this->request( 'bulk' , [
			'input_location' => (is_string($location) ? $this->input[$location] : $location),
			'input' => $data,
		    'callback_url' => $callback,
		    'callback_params' => $params,
		    'orig_name' => $name,
		]);

		return $this;
	}
}