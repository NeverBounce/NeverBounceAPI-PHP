<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Build json
$json = [
    [
        'mike@neverbounce.com',
        'mike',
    ],
    [
        'support@neverbounce.com',
        'support'
    ],
    [
        'invalid@neverbounce.com',
        'invalid'
    ]
];

// Get status from specific job
$job = \NeverBounce\Jobs::createFromArray($json, 'Created from Array', false, false);
$job_id = $job->job_id;

// Check status
$status = \NeverBounce\Jobs::status($job_id);
fwrite(STDOUT, $status->job_status . PHP_EOL);

// Wait until job_status is set to queued
while($status->job_status !== 'queued') {

    // Sleep for a few seconds before querying again
    sleep(3);
    $status = \NeverBounce\Jobs::status($job_id);
    fwrite(STDOUT, $status->job_status . PHP_EOL);
}

// Parse job
$job = \NeverBounce\Jobs::parse($job_id);

var_dump($job);