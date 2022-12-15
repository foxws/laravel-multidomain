<?php

namespace Foxws\MultiDomain;

use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Foxws\MultiDomain\Support\DomainFinder;

class MultiDomain
{
    public function initialize(): void
    {
        /** @var \Foxws\MultiDomain\Support\DomainFinder $domainFinder */
        $domainFinder = app(DomainFinder::class);

        $domain = $domainFinder->findByRequest();

        if (! $domain) {
            return;
        }

        /** @var \Foxws\MultiDomain\Providers\DomainServiceProvider $provider */
        $provider = app(DomainServiceProvider::class, compact('domain'));

        $provider->register();
        $provider->boot();
    }
}
