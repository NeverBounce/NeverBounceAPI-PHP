<?php namespace NeverBounce\Object;

use NeverBounce\Errors\GeneralException;

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
        'valid' => self::VALID,
        'invalid' => self::INVALID,
        'disposable' => self::DISPOSABLE,
        'catchall' => self::CATCHALL,
        'unknown' => self::UNKNOWN,
    ];

    /**
     * Verification constructor.
     * @param string $email
     * @param array $response
     * @throws GeneralException
     */
    public function __construct($email, $response)
    {
        $this->validateResponse($response);

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
     * @param mixed $response
     *
     * @throws GeneralException
     */
    private function validateResponse($response)
    {
        if (!is_array($response)) {
            $exceptionMessage = sprintf(
                'Invalid server response. Array is expected instance of \'%s\' given',
                gettype($response)
            );

            throw new GeneralException($exceptionMessage);
        }
    }

    /**
     * @param $flag
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
            return (in_array($this->result_integer, $types, false) || in_array($this->result, $types, false));
        }

        return ($types == $this->result_integer || $types == $this->result);
    }

    /**
     * @param array|int|string $types
     * @return bool Returns true if the result is not one of the supplied types
     */
    public function not($types)
    {
        if (is_array($types)) {
            return (!in_array($this->result_integer, $types, false) && !in_array($this->result, $types, false));
        }

        return ($types !== $this->result_integer && $types !== $this->result);
    }
}
