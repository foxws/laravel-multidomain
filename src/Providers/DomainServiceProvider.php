<?php

namespace Foxws\MultiDomain\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Foxws\MultiDomain\Livewire\LivewireComponentsFinder;
use Foxws\MultiDomain\Support\Domain;

class DomainServiceProvider extends ServiceProvider
{
    public function __construct(
        protected $app,
        protected array $parameters,
    ) {
    }

    public function register(): void
    {
        $this->registerConfigs();
        $this->registerProviders();

        if (class_exists('\Livewire\LivewireManager')) {
            $this->registerLivewireComponents();
        }
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerWebRoutes();
        $this->registerApiRoutes();
        $this->registerComponents();
        $this->registerTranslations();
    }

    protected function registerConfigs(): void
    {
        $paths = File::glob($this->path('Config/*.php'));

        foreach ($paths as $path) {
            $filename = pathinfo($path, PATHINFO_FILENAME);

            $key = "{$this->name()}.".strtolower($filename);

            $this->mergeConfigFrom($path, $key);
        }
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            $this->path('Resources/Views'),
            $this->name()
        );
    }

    protected function registerWebRoutes(): void
    {
        Route::group([
            'as' => "{$this->name()}.",
            'domain' => $this->domain()->domain,
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(
                $this->path('Routes/web.php')
            );
        });
    }

    protected function registerApiRoutes(): void
    {
        Route::group([
            'as' => "{$this->name()}.",
            'domain' => $this->domain()->domain,
            'prefix' => 'api',
            'middleware' => 'api',
        ], function () {
            $this->loadRoutesFrom(
                $this->path('Routes/api.php')
            );
        });
    }

    protected function registerComponents(): void
    {
        Blade::componentNamespace(
            $this->namespace('Resources/Components'),
            $this->name()
        );
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(
            $this->path('Resources/Translations'),
            $this->name()
        );
    }

    protected function registerLivewireComponents(): void
    {
        $defaultManifestPath = $this->app['livewire']->isRunningServerless()
            ? "/tmp/storage/bootstrap/cache/livewire-{$this->name()}-components.php"
            : $this->app->bootstrapPath("cache/livewire-{$this->name()}-components.php");

        $this->app->bind(LivewireComponentsFinder::class, function () use ($defaultManifestPath) {
            return new LivewireComponentsFinder(
                files: new Filesystem,
                manifestPath: config('livewire.manifest_path') ?: $defaultManifestPath,
                prefix: $this->name(),
                path: $this->path(),
            );
        });
    }

    protected function registerProviders(): void
    {
        $providers = collect(config("{$this->name()}.app.providers", []));
        $providers->each(fn (string $provider) => $this->app->register($provider));
    }

    protected function unregisterProviders(): void
    {
        $providers = collect(config("{$this->name()}.app.providers", []));
        $providers->each(fn (string $provider) => $this->app->instance($provider, null));
    }

    protected function domain(): Domain
    {
        return data_get($this->parameters, 'domain');
    }

    protected function namespace(?string $namespace = null): string
    {
        return str($this->path())
            ->append($namespace)
            ->replaceFirst($this->app->path, '')
            ->prepend(trim($this->app->getNamespace(), '\\'))
            ->replace('/', '\\');
    }

    protected function path(?string $path = null): string
    {
        return str($this->domain()->manifest)
            ->append($path)
            ->replaceFirst('domain.json', '')
            ->replace('\\', '/');
    }

    protected function name(): string
    {
        return str($this->domain()->name)->slug();
    }
}
