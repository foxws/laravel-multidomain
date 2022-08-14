<?php

namespace Foxws\LaravelMultidomain;

use Foxws\LaravelMultidomain\Commands\LaravelMultidomainCommand;
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
            ->hasViews()
            ->hasMigration('create_laravel-multidomain_table')
            ->hasCommand(LaravelMultidomainCommand::class);
    }
}
