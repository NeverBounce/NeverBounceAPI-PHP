<?php namespace NeverBounce\Object;

/**
 * Class VerificationObject
 * @package NeverBounce\Object
 * @property-read  string $email
 * @property-read  string $result
 * @property-read  int $result_integer
 * @property-read  array $flags
 * @property-read  ResponseObject $credits_info
 * @property-read  ResponseObject $address_info
 */
class VerificationObject extends ResponseObject
{

    const GOOD = 0; // Alias of valid
    const VALID = 0;
    const BAD = 1; // Alias of invalid
    const INVALID = 1;
    const DISPOSABLE = 2;
    const CATCHALL = 3;
    const UNKNOWN = 4;

    /**
     * @var array
     */
    static protected $integerCodes = [
        'valid'      => self::VALID,
        'invalid'    => self::INVALID,
        'disposable' => self::DISPOSABLE,
        'catchall'   => self::CATCHALL,
        'unknown'    => self::UNKNOWN,
    ];

    /**
     * Verification constructor.
     * @param string $email
     * @param array $response
     */
    public function __construct($email, $response)
    {
        $response['email'] = $email;
        $response['result_integer'] = self::$integerCodes[$response['result']];
        $response['credits_info'] = new ResponseObject(
            isset($response['credits_info']) ? $response['credits_info'] : []
        );
        $response['address_info'] = new ResponseObject(
            isset($response['address_info']) ? $response['address_info'] : []
        );
        parent::__construct($response);
    }

    /**
     * @param int $flag
     * @return bool Returns true if the specified flag exists
     */
    public function hasFlag($flag)
    {
        return in_array($flag, $this->flags, true);
    }

    /**
     * @param array|int|string $types
     * @return bool Returns true if a the result is one of the supplied types
     */
    public function is($types)
    {
        if (is_array($types)) {
            return (in_array($this->result_integer, $types, true) || in_array($this->result, $types, true));
        }

        return ($types === $this->result_integer || $types === $this->result);
    }

    /**
     * @param array|int|string $types
     * @return bool Returns true if the result is not one of the supplied types
     */
    public function not($types)
    {
        if (is_array($types)) {
            return (!in_array($this->result_integer, $types, true) && !in_array($this->result, $types, true));
        }

        return ($types !== $this->result_integer && $types !== $this->result);
    }
}
