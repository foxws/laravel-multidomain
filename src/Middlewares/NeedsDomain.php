<?php

namespace Foxws\MultiDomain\Middlewares;

use Closure;
use Foxws\MultiDomain\Exceptions\NoCurrentDomain;
use Foxws\MultiDomain\Support\DomainFinder;

class NeedsDomain
{
    public function handle($request, Closure $next)
    {
        $currentDomain = app(DomainFinder::class)->findByRequest();

        if (! $currentDomain) {
            return $this->handleInvalidRequest();
        }

        return $next($request);
    }

    protected function handleInvalidRequest()
    {
        throw NoCurrentDomain::make();
    }
}
