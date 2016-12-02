<?php

// Load files via Composer
require (__DIR__ . '/../vendor/autoload.php');

// Set credentials
\NeverBounce\API\NB_Auth::auth($api_secret_key, $api_key);

// Supply individual emails
$email = 'mike@neverbounce.com';

// Verify email
$resp = \NeverBounce\API\NB_Single::app()->verify($email);

// Handle the response here, view NB_Single for other helper methods or access
// the response directly from $resp->response
if($resp->is([0,3,4])) {

    // Do stuff
    fwrite(STDOUT, sprintf("\nEmail Accepted (%s)", $resp->definition()));

} else {

    // Do other stuff
    fwrite(STDOUT, sprintf("\nEmail Rejected (%s)", $resp->definition()));

}