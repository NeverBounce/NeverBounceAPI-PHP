<?php namespace NeverBounce;

class Auth {

    const DEFAULT_ROUTER = 'api';
    const DEFAULT_VERSION = 'v3';

    /**
     * @var string
     */
    protected static $url = 'https://%s.neverbounce.com/%s/';

    /**
     * @var string
     */
    protected static $router = self::DEFAULT_ROUTER;

    /**
     * @var string
     */
    protected static $version = self::DEFAULT_VERSION;

    /**
     * @var string
     */
    protected static $apiKey;

    /**
     * @var string
     */
    protected static $apiSecret;

    /**
     * @return string
     */
    public static function getRouter()
    {
        return self::$router;
    }

    /**
     * @param string $router
     */
    public static function setRouter($router)
    {
        self::$router = $router;
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        return self::$version;
    }

    /**
     * @param string $version
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

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

    /**
     * @return string
     */
    public static function getApiSecret()
    {
        return self::$apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public static function setApiSecret($apiSecret)
    {
        self::$apiSecret = $apiSecret;
    }

    /**
     * @return string
     */
    public static function getUrl()
    {
        return sprintf(self::$url, self::$router, self::$version);
    }
}