<?php

namespace Foxws\LaravelMultidomain\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxws\LaravelMultidomain\LaravelMultidomain
 */
class LaravelMultidomain extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foxws\LaravelMultidomain\LaravelMultidomain::class;
    }
}
