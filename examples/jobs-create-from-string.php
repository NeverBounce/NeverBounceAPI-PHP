<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Get contents from file
$string = file_get_contents(__DIR__ . '/test.csv');

// Get status from specific job
$job = \NeverBounce\Jobs::createFromString($string, 'Created from String');

var_dump($job);