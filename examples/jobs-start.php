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
$job = \NeverBounce\Jobs::createFromArray($json, 'Created from Array', false, true, false);

$job_id = $job->job_id;

sleep(3);
$status = \NeverBounce\Jobs::status($job_id);
fwrite(STDOUT, $status->job_status . PHP_EOL);

while($status->job_status !== 'waiting') {
    sleep(3);

    $status = \NeverBounce\Jobs::status($job_id);
    fwrite(STDOUT, $status->job_status . PHP_EOL);

}

sleep(5);
$job = \NeverBounce\Jobs::start($job_id);

var_dump($job);