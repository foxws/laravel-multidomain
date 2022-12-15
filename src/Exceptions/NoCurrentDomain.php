<?php

namespace Foxws\MultiDomain\Exceptions;

use Exception;

class NoCurrentDomain extends Exception
{
    public static function make()
    {
        return new static('The request expected a current domain but none was set.');
    }
}
