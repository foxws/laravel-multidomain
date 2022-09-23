<?php

namespace Foxws\MultiDomain;

use Foxws\LaravelMultidomain\Commands\CacheCommand;
use Foxws\LaravelMultidomain\Commands\ClearCommand;
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
                CacheCommand::class,
                ClearCommand::class,
            );
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('multidomain', MultiDomain::class);
        $this->app->bind('multidomain', MultiDomain::class);
    }

    public function packageBooted(): void
    {
        $this->app->make(MultiDomain::class)->initialize();
    }
}
