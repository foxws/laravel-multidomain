<?php

namespace Foxws\LaravelMultidomain\Providers;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app[RepositoryInterface::class]->boot();
    }

    public function register(): void
    {
        $this->app[RepositoryInterface::class]->register();
    }
}
