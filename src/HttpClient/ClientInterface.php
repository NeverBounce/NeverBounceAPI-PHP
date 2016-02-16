<?php namespace NeverBounce\HttpClient;

interface ClientInterface
{
    /**
     * @param string $url The URL being requested, including domain and protocol
     * @param array $params KV pairs for parameters. Can be nested for arrays and hashes
     * @throws Error\Api & Error\ApiConnection
     * @return array($rawBody, $httpStatusCode, $httpHeader)
     */
    public function request($url, array $params);
}