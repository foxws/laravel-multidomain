<?php

namespace Foxws\MultiDomain\Middlewares;

use Closure;
use Foxws\MultiDomain\Support\DomainFinder;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidDomainSession
{
    public function handle($request, Closure $next)
    {
        $currentDomain = app(DomainFinder::class)->findByRequest();

        $sessionKey = 'ensure_valid_domain_session_name';

        if (! $request->session()->has($sessionKey)) {
            $request->session()->put($sessionKey, $currentDomain->name);

            return $next($request);
        }

        if ($request->session()->get($sessionKey) !== $currentDomain->name) {
            return $this->handleInvalidDomainSession($request);
        }

        return $next($request);
    }

    protected function handleInvalidDomainSession($request)
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
