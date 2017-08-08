<?php namespace NeverBounce;

class Auth
{
    /**
     * @var string
     */
    protected static $apiKey;

    /**
     * @return string
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
}
