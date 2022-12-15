<?php

namespace Foxws\MultiDomain;

use Illuminate\Foundation\Application;
use Foxws\MultiDomain\Commands\ClearCommand;
use Foxws\MultiDomain\Livewire\LivewireManager as LivewireLivewireManager;
use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Foxws\MultiDomain\Support\DomainFinder;
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
        $this->app->bind(Multidomain::class);
        $this->app->alias(Multidomain::class, 'multidomain');

        $this->app->bind(DomainFinder::class);
        $this->app->bind(DomainServiceProvider::class, fn (Application $app, array $parameters) => new DomainServiceProvider($app, $parameters));

        if (class_exists('\Livewire\LivewireManager')) {
            $this->app->singleton(\Livewire\LivewireManager::class, LivewireLivewireManager::class);
        }
    }

    public function packageBooted(): void
    {
        app(MultiDomain::class)->initialize();
    }
}
