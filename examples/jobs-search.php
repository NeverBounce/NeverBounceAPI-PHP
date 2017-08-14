<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Search jobs in account
$jobs = \NeverBounce\Jobs::search([
//    'job_id' => 10000, // Filter jobs based on id
//    'filename' => 'Book1.csv', // Filter jobs based on filename
//    'completed' => true, // Show completed only
//    'job_status' => 'complete', // Show completed jobs only
//    'page' => 1, // Page to start from
//    'items_per_page' => 10, // Number of items per page
]);

var_dump($jobs);
