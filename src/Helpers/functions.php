<?php

use Foxws\LaravelMultidomain\Domain;

if (! function_exists('domain')) {
    function domain(string $name): ?Domain
    {
        return app('domains')->find($name);
    }
}
