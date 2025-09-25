<?php

return [
    'title' => env('APP_NAME', 'API Docs'),
    'base_url' => env('APP_URL', 'http://localhost').'/api',
    'routes' => [
        [
            'match' => [
                'domains' => ['*'],
                'prefixes' => ['api/*'],
                'versions' => ['v1'],
            ],
            'include' => ['*'],
            'exclude' => [],
        ],
    ],
];


