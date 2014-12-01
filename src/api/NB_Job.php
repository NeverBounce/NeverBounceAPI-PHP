<?php namespace NeverBounce\API;

/**
 * Class NB_Job
 *
 * @package NeverBounce\API
 */
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
	 * @param $id
	 * @param $job
	 *
	 * @return \NeverBounce\API\NB_Job
	 */
	public function __construct($id, $job) {
		$this->id = (integer) $id;
		$this->filename = (string) $job->orig_name;
		$this->status = (integer) $job->state;
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