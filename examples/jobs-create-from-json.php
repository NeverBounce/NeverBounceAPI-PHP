<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Build json
$json = [
    'mike@neverbounce.com',
    'support@neverbounce.com',
    'invalid@neverbounce.com'
];

// Get status from specific job
$job = \NeverBounce\Jobs::createFromJson($json, 'Created from String');

var_dump($job);