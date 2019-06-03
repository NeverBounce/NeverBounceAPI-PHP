<?php namespace NeverBounce;

class Utils
{
    /**
     * Returns wrapper version
     * @deprecated 4.2.1 Use ApiClient::VERSION instead
     * @return string
     */
    public static function wrapperVersion()
    {
        return ApiClient::VERSION;
    }
}
