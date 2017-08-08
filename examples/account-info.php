<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Make request for API info
$info = \NeverBounce\Account::info();

// Dump account info
var_dump($info);
