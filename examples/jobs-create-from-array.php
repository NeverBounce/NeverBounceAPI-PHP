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
$job = \NeverBounce\Jobs::createFromArray($json, 'Created from Array');

var_dump($job);