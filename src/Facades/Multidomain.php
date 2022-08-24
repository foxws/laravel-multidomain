<?php

namespace Foxws\LaravelMultidomain\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxws\LaravelMultidomain\LaravelMultidomain
 */
class Multidomain extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foxws\LaravelMultidomain\Domain::class;
    }
}
