<?php

declare(strict_types=1);

return [
    'http' => [
        400 => 'The server could not understand the request due to invalid syntax.',
        401 => 'Access denied due to invalid credentials.',
        402 => 'Payment required to access the requested resource.',
        403 => 'Access denied to the requested resource.',
        404 => 'The requested resource could not be found.',
        500 => 'An internal error or misconfiguration.',
    ],
    'query' => [
        500 => 'The server possibly overloaded or otherwise not running properly.',
    ],
    'common' => [
        'success' => 'Success.',
        'error' => 'Failure.',
    ],
];
