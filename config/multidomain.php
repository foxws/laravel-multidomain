<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Domains path
    |--------------------------------------------------------------------------
    |
    | This path used for save the generated domain. This path also will be added
    | automatically to list of scanned folders.
    |
    */

    'path' => app_path('Domain'),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => true,
        'paths' => [
            app_path('Domain/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */

    'cache' => [
        'enabled' => false,
        'key' => 'multidomain',
        'lifetime' => 60 * 60 * 24,
    ],
];
