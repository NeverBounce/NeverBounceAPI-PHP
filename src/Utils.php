<?php namespace NeverBounce;

class Utils
{
    /**
     * Returns wrapper version
     * @return string|false
     */
    public static function wrapperVersion()
    {
        return file_get_contents(__DIR__ . '/../VERSION');
    }
}
