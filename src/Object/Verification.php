<?php namespace NeverBounce\Object;

class Verification {

    const GOOD = 0;
    const VALID = 0;
    const BAD = 1;
    const INVALID = 1;
    const DISPOSABLE = 2;
    const CATCHALL = 3;
    const UNKNOWN = 4;

    /**
     * @var array
     */
    protected $textCodes = [
        self::VALID => 'valid',
        self::INVALID=> 'invalid',
        self::DISPOSABLE => 'disposable',
        self::CATCHALL => 'catchall',
        self::UNKNOWN => 'unknown',
    ];

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $code;

    /**
     * Verification constructor.
     * @param string $email
     * @param int $code
     */
    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getTextCode()
    {
        return $this->textCodes[$this->code];
    }

    /**
     * @param array|int $types
     * @return bool
     */
    public function is($types)
    {
        if (is_array($types)) {
            if (in_array($this->code, $types)) {
                return true;
            }
        } else {
            if ($types == $this->code) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $types
     * @return bool
     */
    public function not($types)
    {
        if (is_array($types)) {
            if (!in_array($this->code, $types)) {
                return true;
            }
        } else {
            if ($types !== $this->code) {
                return true;
            }
        }

        return false;
    }
}