<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Verify a single email
$verification = \NeverBounce\Single::check('mike@yaho.com');

// Get verified email
stdout('Email Verified: ' . $verification->email);

// Get numeric verification result
stdout('Numeric Code: ' . $verification->result_integer);

// Get text based verification result
stdout('Text Code: ' . $verification->result);

// Check for dns flag
stdout('Has DNS: ' . (string) $verification->hasFlag('has_dns'));

// Check for free_email_host flag
stdout('Is free mail: ' . (string) $verification->hasFlag('free_email_host'));

// Get numeric verification result
stdout('Suggested Correction: ' . $verification->suggested_correction);

// Check if email is valid
stdout('Is unknown: ' . (string) $verification->is('unknown'));

// Get numeric verification result
stdout('Isn\'t valid or catchall: ' . (string) $verification->not(['valid', 'catchall']));

// Get credits used
$credits = ($verification->credits_info->paid_credits_used
    + $verification->credits_info->free_credits_used);
stdout('Credits used: ' . $credits);
