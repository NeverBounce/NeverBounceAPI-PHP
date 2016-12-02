<?php

// Load files via Composer
require (__DIR__ . '/../vendor/autoload.php');

// Set credentials
\NeverBounce\API\NB_Auth::auth($api_secret_key, $api_key);

// Set filename
$filename = 'SupliedInput.csv';

// Create the input; NOTE: do not use \n or \r for newlines directly in the
// string. When it comes time to encode them they get encoded into literal
// strings rather than as newline characters. The best way to combat this is to
// either read the contents of a csv with get_file_contents or by specifying the
// input like we have below
$input = 'mike@neverbounce.com
support@neverbounce.com
invalid@neverbounce.com';

// Create the job
$resp = \NeverBounce\API\NB_Bulk::app()->create(
    $input,
    \NeverBounce\API\NB_Bulk::SUPPLIED_DATA,
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