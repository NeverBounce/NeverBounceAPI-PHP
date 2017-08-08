<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Build json
$json = [
    [
        'id' => '12345',
        'email' => 'support@neverbounce.com',
        'name' => 'Fred McValid',
    ],
    [
        'id' => '12346',
        'email' => 'invalid@neverbounce.com',
        'name' => 'Bob McInvalid',
    ],
];

// Get status from specific job
$job = \NeverBounce\Jobs::create(
    $json,
    \NeverBounce\Jobs::SUPPLIED_INPUT,
    'Created from Array',
    false,
    true,
    false
);
$job_id = $job->job_id;

// Check status
$status = \NeverBounce\Jobs::status($job_id);
fwrite(STDOUT, $status->job_status . PHP_EOL);

// Wait until job_status is set to waiting
while ($status->job_status !== 'waiting') {
    // Wait a few seconds before querying again
    sleep(3);
    $status = \NeverBounce\Jobs::status($job_id);
    fwrite(STDOUT, $status->job_status . PHP_EOL);
}

// Start Job
$job = \NeverBounce\Jobs::start($job_id);

var_dump($job);
