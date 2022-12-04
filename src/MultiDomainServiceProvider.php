<?php

namespace Foxws\MultiDomain;

use Foxws\MultiDomain\Commands\ClearCommand;
use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Illuminate\Foundation\Application as FoundationApplication;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MultiDomainServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-multidomain')
            ->hasConfigFile('multidomain')
            ->hasCommands(
                ClearCommand::class,
            );
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Multidomain::class);
        $this->app->alias(Multidomain::class, 'multidomain');

        $this->app->singleton(DomainServiceProvider::class, fn (FoundationApplication $app, array $parameters) => new DomainServiceProvider($app, $parameters));
    }
}
