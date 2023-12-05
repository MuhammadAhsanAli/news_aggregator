<?php

return [
    'services' => [
        'guardian' => [
            'apiKey'   => env('GUARDIAN_API_KEY'),
            'baseUrl'  => env('GUARDIAN_BASE_URL'),
            'pageSize' => env('GUARDIAN_PAGE_SIZE'),
        ],
        'news_api' => [
            'apiKey'  => env('NEWS_API_KEY'),
            'baseUrl' => env('NEWS_API_BASE_URL'),
        ],
        'ny_times' => [
            'apiKey'  => env('NY_TIMES_API_KEY'),
            'baseUrl' => env('NY_TIMES_API_BASE_URL'),
        ],
    ],
    'dates' => [
        'from' => now()->format('Y-m-d'),
        'to'   => now()->format('Y-m-d'),
    ]
];
