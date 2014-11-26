<?php namespace NeverBounce\API;

/**
 * Class NB_Job
 *
 * @package NeverBounce\API
 */
class NB_Job {

	public $finished, $total, $processed, $valid, $invalid, $catchall, $disposable, $unknown;

	/**
	 * @param $id
	 * @param $job
	 *
	 * @return \NeverBounce\API\NB_Job
	 */
	public function __construct($id, $job) {
		$this->id = (integer) $id;
		$this->finished = (bool) $job->finished;
		$this->total = (integer) $job->total;
		$this->processed = (integer) $job->processed;
		$this->valid = (integer) $job->valid;
		$this->invalid = (integer) $job->invalid;
		$this->catchall = (integer) $job->catchall;
		$this->disposable = (integer) $job->disposable;
		$this->unknown = (integer) $job->unknown;

		return $this;
	}

	/**
	 * @param string $of
	 *
	 * @return float
	 */
	public function percentage($of = 'total') {
		return (float) ($this->$of/$this->total*100);
	}
}