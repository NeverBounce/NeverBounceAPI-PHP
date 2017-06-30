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
        'name' => 'Fred McValid'
    ],
    [
        'id' => '12346',
        'email' => 'invalid@neverbounce.com',
        'name' => 'Bob McInvalid'
    ]
];

// Get status from specific job
$job = \NeverBounce\Jobs::createFromArray($json, 'Created from Array.csv', false, true, true);

var_dump($job);