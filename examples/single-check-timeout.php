<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Verify a single email
$verifiation = \NeverBounce\Single::verify('mike@yaho.com');

// Get verified email
fwrite(STDOUT, 'Email Verified: ' . $verifiation->email . PHP_EOL);

// Get numeric verification result
fwrite(STDOUT, 'Numeric Code: ' . $verifiation->result_integer . PHP_EOL);

// Get text based verification result
fwrite(STDOUT, 'Text Code: ' . $verifiation->result . PHP_EOL);

// Check for dns flag
fwrite(STDOUT, 'Has DNS: ' . (string) $verifiation->hasFlag('has_dns') . PHP_EOL);

// Check for free_email_host flag
fwrite(STDOUT, 'Is free mail: ' . (string) $verifiation->hasFlag('free_email_host') . PHP_EOL);

// Get numeric verification result
fwrite(STDOUT, 'Suggested Correction: ' . $verifiation->suggested_correction . PHP_EOL);

// Check if email is valid
fwrite(STDOUT, 'Is valid: ' . (string) $verifiation->is('unknown') . PHP_EOL);

// Get numeric verification result
fwrite(STDOUT, 'Isn\'t valid or catchall: ' . (string) $verifiation->not(['valid', 'catchall']) . PHP_EOL);

// Get credits used
fwrite(STDOUT, 'Credits used: ' . ($verifiation->credits_info->paid_credits_used + $verifiation->credits_info->free_credits_used) . PHP_EOL);