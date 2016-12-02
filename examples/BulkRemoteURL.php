<?php

// Load files via Composer
require (__DIR__ . '/../vendor/autoload.php');

// Set credentials
\NeverBounce\API\NB_Auth::auth($api_secret_key, $api_key);

// Set filename
$filename = 'RemoteURL.csv';

// Create the input; NOTE: The URL must be accessible with only the url; the
// file cannot be behind basic HTTP auth. However using a tokenized (aka expiring)
// URL is perfectly acceptable. A short lifetime like 5 minutes should be an
// adequate amount of time for the API to retrieve the CSV data
$input = 'https://example.com/test.csv';

// Create the job
$resp = \NeverBounce\API\NB_Bulk::app()->create(
    $input,
    \NeverBounce\API\NB_Bulk::REMOTE_URL,
    $filename
);

// Get job item
$job = $resp->first();
fwrite(STDOUT, "\nStarting: {$job->state}");

// Wait for job's state to indicate either completion (4), failure (5)
// or deletion (6)
while(!in_array($job->state, [4,5,6])) {
    // Processing can take some time so we'll wait a few seconds before
    // querying the job status
    sleep(5);
    fwrite(STDOUT, "\nWaiting: {$job->state}");

    // Query the job status
    $job = \NeverBounce\API\NB_Bulk::app()->retrieve($job->id)->first();
}

// Job has finished!
fwrite(STDOUT, "\nFinished: {$job->state}\n");
$job_data = \NeverBounce\API\NB_Bulk::app()->download($job->id);
var_dump($job_data);