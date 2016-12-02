<?php

// Load files via Composer
require (__DIR__ . '/../vendor/autoload.php');

// Set credentials
\NeverBounce\API\NB_Auth::auth($api_secret_key, $api_key);

// Set filename
$filename = 'SupliedInput.csv';

// Input can be supplied data or a remote url. Please review BulkRemoteURL.php
// and BulkSuppliedData.php for recommendations for each
$input = 'mike@neverbounce.com
support@neverbounce.com
invalid@neverbounce.com';

// Create the job
$resp = \NeverBounce\API\NB_Bulk::app()->sample(
    $input,
    \NeverBounce\API\NB_Bulk::SUPPLIED_DATA,
    $filename
);

// Get job item
$job = $resp->first();
fwrite(STDOUT, "\nStarting: {$job->state}");

// Wait for job to either complete the sample, fail (5) or be deleted (6). When
// a sample is run the sample status will be found in the job details. Once it
// completes the job's state will be set to waiting (2) and
// job_details->sample_status to complete (3)
while(!in_array($job->state, [2,5,6]) && $job->job_details->sample_status != 3) {

    // Processing can take some time so we'll wait a few seconds before
    // querying the job status
    sleep(5);
    fwrite(STDOUT, "\nWaiting on sample: {$job->job_details->sample_status}");

    // Query the job status
    $job = \NeverBounce\API\NB_Bulk::app()->retrieve($job->id)->first();
}

// Make sure job's state is set to waiting (2) otherwise assume either the job
// has been marked as failed, deleted or another issue has arose
if($job->state === 2) {

    // The estimated bounce threshold at which we should automatically run
    // full verification on the job (float 0.0 - 1.0; 1.0 === 100%)
    $bounce_threshold = 0.3;

    // Check the job's bounce estimate (float between 0.0 and 1.0; 1.0 === 100%),
    // if over a certain threshold run the job automatically
    if($job->job_details->sample_details->bounce_estimate >= $bounce_threshold) {

        fwrite(STDOUT, "\nStarting full verification...");
        fwrite(STDOUT, "\nEstimated bounce rate: {$job->job_details->sample_details->bounce_estimate}");

        // Start full verification for job
        $resp = \NeverBounce\API\NB_Bulk::app()->start($job->id);
        $job = $resp->first();

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

    } else {

        // No further action required, job does not need additional verification
        fwrite(STDOUT, "\nJob does not require further verification");
        fwrite(STDOUT, "\nEstimated bounce rate: {$job->job_details->sample_details->bounce_estimate}");

    }

} else {

    // Job likely either failed, was deleted or was started from the dashboard
    fwrite(STDOUT, "\nJob status was not set to waiting: {$job->state}");
    var_dump($job);
}