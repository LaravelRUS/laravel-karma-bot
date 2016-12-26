<?php declare(strict_types = 1);
return [
    /*
    |--------------------------------------------------------------------------
    | Annotations cache
    |--------------------------------------------------------------------------
    |
    | `true`  - Cache is enabled
    | `false` - Cache is disabled
    |
    */
    'cache' => env('ANNOTATIONS_CACHE', !env('APP_DEBUG', false)),

    /*
    |--------------------------------------------------------------------------
    | Doctrine debug mode
    |--------------------------------------------------------------------------
    */
    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Available annotations namespace prefixes
    |--------------------------------------------------------------------------
    |
    | If you want try to search annotations in all available namespaces - just sets empty array
    |
    */
    'namespaces' => [
        'KarmaBot\\Bot\\Mapping'
    ]
];
