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
        'catchall' => self::CATACHALL,
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
        self::CATACHALL => 'Catchall',
        self::UNKNOWN => 'Unknown',
    ];

    /**
     * This performs a single validation
     *
     * @param string $email The email to verify
     *
     * @return $this
     */
    public function verify($email)
    {
        // Sanitize aliases; http_build_query does not encode (+) and
        // x-www-urlencode-form treats these as spaces
        $email = str_replace('+', '%2B', $email);

        $this->request('single', ['email' => $email]);

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
