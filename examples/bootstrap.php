<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../.api-key.php';

/**
 * @param $msg
 */
function stdout($msg)
{
    fwrite(STDOUT, $msg . PHP_EOL);
}

if (empty($api_key)) {
    throw new Exception(
        'The API key was not defined before running the '
        . 'examples. Create a `.env` file in the root directory '
        . 'of this package and specify the API_KEY before running '
        . 'the examples.'
    );
}

\NeverBounce\Auth::setApiKey($api_key);
