<?php

use Foxws\MultiDomain\Domains\Domain\MultiDomain;

if (! function_exists('namespace_path')) {
    function namespace_path(string $namespace)
    {
        $name = str($namespace)
            ->finish('\\')
            ->replaceFirst(app()->getNamespace(), '');

        return app('path').'/'.str_replace('\\', '/', $name);
    }
}

if (! function_exists('domain')) {
    function domain(string $domain): ?MultiDomain
    {
        return app('multidomain')
            ->findByDomain($domain)
            ->first();
    }
}
