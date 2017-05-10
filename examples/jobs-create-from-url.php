<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

$url = 'https://example.com/test.csv';

// Get status from specific job
$job = \NeverBounce\Jobs::createFromUrl($url, 'Created from URL');

var_dump($job);