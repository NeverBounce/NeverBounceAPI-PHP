<?php

namespace NeverBounce\HttpClient;

interface HttpClientInterface
{
    /**
     * @param string $url
     */
    public function init($url);

    /**
     * @param int $name Curl option name
     * @param mixed $value Curl option value
     */
    public function setOpt($name, $value);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @param int $name
     * @return mixed
     */
    public function getInfo($name);

    /**
     * @return mixed
     */
    public function getErrno();

    /**
     * @return mixed
     */
    public function getError();

    /**
     * @return mixed
     */
    public function close();
}
