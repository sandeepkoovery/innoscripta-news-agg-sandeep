<?php

return [
    'news_api' => [
        'base_url' => 'https://newsapi.org/v2',
        'api_key' => env('NEWS_API_KEY'),
    ],
    'bbc' => [
        'base_url' => 'https://api.bbc.co.uk/news',
        'api_key' => env('BBC_API_KEY'),
    ],
];
