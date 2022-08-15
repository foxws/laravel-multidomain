<?php

namespace Foxws\LaravelMultidomain;

use Foxws\LaravelMultidomain\Commands\CacheCommand;
use Foxws\LaravelMultidomain\Commands\ClearCommand;
use Foxws\LaravelMultidomain\Providers\BootstrapServiceProvider;
use Foxws\LaravelMultidomain\Providers\DomainServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMultidomainServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-multidomain')
            ->hasConfigFile()
            ->hasCommand(
                CacheCommand::class,
                ClearCommand::class,
            );
    }

    public function registeringPackage()
    {
        $this->app->register(DomainServiceProvider::class);
        $this->app->register(BootstrapServiceProvider::class);
    }
}
