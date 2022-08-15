<?php

namespace Foxws\LaravelMultidomain\Providers;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Foxws\LaravelMultidomain\Support\DomainRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerServices();
    }

    public function provides(): array
    {
        return [RepositoryInterface::class, 'domains'];
    }

    protected function registerServices(): void
    {
        $this->app->singleton(RepositoryInterface::class, fn (Application $app) => new DomainRepository($app));

        $this->app->alias(RepositoryInterface::class, 'domains');
    }
}
