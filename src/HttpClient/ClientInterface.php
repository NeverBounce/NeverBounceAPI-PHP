<?php namespace NeverBounce\HttpClient;

interface ClientInterface
{
    /**
     * @param string $url The URL being requested, including domain and protocol
     * @param array $params KV pairs for parameters. Can be nested for arrays and hashes
     * @param bool $auth
     * @return array & Error\ApiConnection
     */
    public function request($url, array $params, $auth = false);
}