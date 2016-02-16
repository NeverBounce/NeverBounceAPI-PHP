<?php namespace NeverBounce;

class TestCase extends \PHPUnit_Framework_TestCase {
    
    protected function generateHeaders() {
        return [
            "Access-Control-Allow-Origin" => "*",
            "Cache-Control" => "no-store",
            "Connection" => "keep-alive",
            "Content-Length" => "120",
            "Content-Type" => "application/json",
            "Date" => "Tue, 16 Feb 2016 04:05:43 GMT",
            "Pragma" => "no-cache",
            "Server" => "nginx/1.4.6 (Ubuntu)",
            "X-Powered-By" => "HHVM/3.11.0",
        ];
    }
    
}