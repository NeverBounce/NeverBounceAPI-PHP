<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Get status from specific job
$job = \NeverBounce\Jobs::status(185137);

var_dump($job);