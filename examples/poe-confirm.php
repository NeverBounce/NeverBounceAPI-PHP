<?php

/**
 * Authentication is setup in bootstrap file
 */
require_once __DIR__ . '/bootstrap.php';

// Get status from specific job
$job = \NeverBounce\PoE::confirm('support@neverbounce.com', 'e3173fdbbdce6bad26522dae792911f2', 'NBPOE-TXN-5942940c09669');

var_dump($job);