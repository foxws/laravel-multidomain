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
    | Domain path
    |--------------------------------------------------------------------------
    |
    | This defines the domain root path.
    |
    */

    'path' => app_path('Domain'),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | This defines the path to scan for domains.
    |
    */

    'scan' => [
        'enabled' => true,
        'path' => app_path('Domain/*'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | This defines config for setting up caching.
    |
    */

    'cache_store' => env('MULTIDOMAIN_CACHE_DRIVER', 'redis'),

    'cache_tag' => env('MULTIDOMAIN_CACHE_TAG', ''),
];
