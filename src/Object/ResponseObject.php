<?php

namespace NeverBounce\Object;

class ResponseObject
{

    /**
     * @var array
     */
    protected $response;

    /**
     * ResponseObject constructor.
     * @param array $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->response[$name])) {
            return $this->response[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        // Do nothing
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->response[$name]);
    }
}