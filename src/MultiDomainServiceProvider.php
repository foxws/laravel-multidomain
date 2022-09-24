<?php

namespace Foxws\MultiDomain;

use Foxws\MultiDomain\Commands\CacheCommand;
use Foxws\MultiDomain\Commands\ClearCommand;
use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MultiDomainServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-multidomain')
            ->hasConfigFile()
            ->hasCommands(
                ClearCommand::class,
            );
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MultiDomain::class, function ($app) {
            return new MultiDomain($app->make(MultiDomainRepository::class));
        });

        $this->app->bind('multidomain', MultiDomain::class);

        $this->app->bind(DomainServiceProvider::class, function ($app, $domain) {
            return new DomainServiceProvider($app, $domain);
        });
    }

    public function packageBooted(): void
    {
        $this->app->make(MultiDomain::class)->boot();
    }
}
