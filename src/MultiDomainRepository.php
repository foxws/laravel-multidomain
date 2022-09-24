<?php

namespace Foxws\MultiDomain;

use Foxws\MultiDomain\Domains\Domain\MultiDomain;
use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Foxws\MultiDomain\Support\MultiDomainFinder;
use Illuminate\Foundation\Application;

class MultiDomainRepository
{
    public function __construct(
        protected Application $app,
    ) {
    }

    public function build(): void
    {
        $domains = $this->all();

        foreach ($domains as $domain) {
            $this->register($domain);
        }
    }

    public function all(): array
    {
        return $this->app->make(MultiDomainFinder::class)->domains();
    }

    protected function register(MultiDomain $domain): void
    {
        $provider = $this->app->make(DomainServiceProvider::class, compact('domain'));

        $provider->register();
        $provider->boot();
    }
}
