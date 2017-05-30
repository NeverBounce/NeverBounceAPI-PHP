<?php namespace NeverBounce\API;

/**
 * Class NB_Single
 *
 * @package NeverBounce\API
 */
class NB_Single
{
    use NB_App;

    /**
     * Value of a verified good email
     */
    const GOOD = 0;

    /**
     * Value of a verified bad email
     */
    const BAD = 1;

    /**
     * Value of a disposable email
     */
    const DISPOSABLE = 2;

    /**
     * Value of a catchall email
     */
    const CATCHALL = 3;

    /**
     * Value of a catchall email.
     * @deprecated Original version with typo.  Retained for backwards compatability.
     */
    const CATACHALL = 3;

    /**
     * Value of an unknown email
     */
    const UNKNOWN = 4;

    /**
     * Type string definitions
     *
     * @var array
     */
    protected $types = [
        'valid' => self::GOOD,
        'good' => self::GOOD, // Alias
        'invalid' => self::BAD,
        'bad' => self::BAD, // Alias
        'disposable' => self::DISPOSABLE,
        'catchall' => self::CATCHALL,
        'unknown' => self::UNKNOWN,
    ];

    /**
     * Type string definitions
     *
     * @var array
     */
    protected $definitions = [
        self::GOOD => 'Valid',
        self::BAD => 'Invalid',
        self::DISPOSABLE => 'Disposable',
        self::CATCHALL => 'Catchall',
        self::UNKNOWN => 'Unknown',
    ];

    /**
     * This performs a single validation
     * The timeout for this method is not the same as the cUrl timeout! It is
     * not a connection timeout but rather a timeout for how long the API will
     * attempt to verify the email. It is also a soft timeout; i.e. a value of 5
     * may spend go over the timeout by a few milliseconds before giving up.
     *
     * @param string $email The email to verify
     * @param int $timeout The maximum time to spend verifying the email; this is an approximate timeout not a hard timeout
     * @return $this
     * @throws NB_Exception
     */
    public function verify($email, $timeout = null)
    {
        $params = [
            'email' => $email
        ];

        if($timeout) {
            if(!is_int($timeout))
                throw new NB_Exception("Timeout should be expressed in seconds and must be an integer; " . gettype($timeout) . " given");

            $params['timeout'] = $timeout;
        }

        $this->request('single', $params);

        return $this;
    }

	/**
	 * This returns the true or false depending on the flags passed via $types
	 *
	 * @param mixed $types
	 *
	 * @return bool
	 */
    public function is($types)
    {
        if (is_array($types)) {
            foreach ($types as $type) {
                if (!is_numeric($type) && $this->types[$type] == $this->response->result) {
                    return true;
                }

                if (is_numeric($type) && $type == $this->response->result) {
                    return true;
                }
            }
        } else {

            if (!is_numeric($types) && $this->types[$types] == $this->response->result) {
                return true;
            }

            if (is_numeric($types) && $types == $this->response->result) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the definition for the state
     * @return string
     */
    public function definition()
    {
        if (isset($this->definitions[$this->response->result]))
            return $this->definitions[$this->response->result];

        return 'N/A';
    }
}
