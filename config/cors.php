<?php

return [
    'paths' => ['*'], // Applies to all routes
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://uattims-bird.nabard.org', // Only the domain here
    ],
    'allowed_origins_patterns' => [], // Keep empty unless strictly necessary
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 3600,
    'supports_credentials' => true, // Change to true if using cookies/sessions
];