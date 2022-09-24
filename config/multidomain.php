<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Domain namespace
    |--------------------------------------------------------------------------
    |
    | This defines the domain root namespace.
    |
    */

    'namespace' => 'App\\Domain',

    /*
    |--------------------------------------------------------------------------
    | Domain caching
    |--------------------------------------------------------------------------
    |
    | This defines the domain caching.
    |
    */

    'cache_enabled' => env('MULTIDOMAIN_CACHE_ENABLED', false),

    'cache_lifetime' => env('MULTIDOMAIN_CACHE_LIFETIME', 60 * 60 * 24 * 7),
];
