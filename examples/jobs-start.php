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

// Sleep is just for demonstration
sleep(15);

$job = \NeverBounce\Jobs::start($job_id);

var_dump($job);