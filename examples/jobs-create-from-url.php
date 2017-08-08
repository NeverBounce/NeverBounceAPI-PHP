<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

$url = 'https://example.com/test.csv';

// Get status from specific job
$job = \NeverBounce\Jobs::create(
    $url,
    \NeverBounce\Jobs::REMOTE_INPUT,
    'Created from URL.csv'
);

var_dump($job);
