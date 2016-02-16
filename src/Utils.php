<?php namespace NeverBounce;

class Utils {

    /**
     * Returns wrapper version
     * @return string
     */
    public static function wrapperVersion() {
        return file_get_contents(__DIR__ . '../VERSION');
    }

}