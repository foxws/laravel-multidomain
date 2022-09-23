<?php

namespace Foxws\LaravelMultidomain\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxws\MultiDomain\MultiDomain
 */
class MultiDomain extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foxws\MultiDomain\Domain::class;
    }
}
