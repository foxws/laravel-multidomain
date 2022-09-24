<?php

namespace Foxws\MultiDomain\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Foxws\MultiDomain\MultiDomain
 */
class MultiDomain extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Foxws\MultiDomain\MultiDomain::class;
    }
}
