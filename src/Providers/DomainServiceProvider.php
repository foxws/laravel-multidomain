<?php

namespace Foxws\MultiDomain\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerWebRoutes();
        $this->registerApiRoutes();
        $this->registerComponents();
        $this->registerTranslations();
    }

    public function registerConfigs(): void
    {
        $paths = File::glob($this->path('Config/*.php'));

        foreach ($paths as $path) {
            $filename = pathinfo($path, PATHINFO_FILENAME);

            $key = "{$this->lowerName()}.".strtolower($filename);

            $this->mergeConfigFrom($path, $key);
        }
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom(
            $this->path('Resources/Views'),
            $this->lowerName()
        );
    }

    public function registerWebRoutes(): void
    {
        Route::group([
            'as' => "{$this->lowerName()}.",
            'domain' => $this->domain(),
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(
                $this->path('Routes/web.php')
            );
        });
    }

    public function registerApiRoutes(): void
    {
        Route::group([
            'as' => "{$this->lowerName()}.",
            'domain' => $this->domain(),
            'prefix' => 'api',
            'middleware' => 'api',
        ], function () {
            $this->loadRoutesFrom(
                $this->path('Routes/api.php')
            );
        });
    }

    public function registerComponents(): void
    {
        $namespace = $this->namespace().'\\Resources\\Components';

        Blade::componentNamespace($namespace, $this->lowerName());
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(
            $this->path('Resources/Translations'),
            $this->lowerName()
        );
    }

    public function registerProviders(): void
    {
        $providers = collect(config("{$this->lowername()}.app.providers", []));
        $providers->each(fn (string $provider) => $this->app->register($provider));
    }

    public function unregisterProviders(): void
    {
        $providers = collect(config("{$this->lowername()}.app.providers", []));
        $providers->each(fn (string $provider) => $this->app->instance($provider, null));
    }

    protected function attributes(): array
    {
        return $this->parameters['domain']->toArray();
    }

    protected function attribute(string $key, mixed $default = null): mixed
    {
        return data_get($this->attributes(), $key, $default);
    }

    protected function name(): string
    {
        return $this->attribute('name');
    }

    protected function lowerName(): string
    {
        return strtolower($this->name());
    }

    protected function domain(): string
    {
        return match ($this->app->environment()) {
            'production' => $this->attribute('domain.production'),
            'staging' => $this->attribute('domain.staging'),
            default => $this->attribute('domain.local'),
        };
    }

    protected function path(string $path = ''): string
    {
        return namespace_path($this->namespace()).$path;
    }

    protected function namespace(): string
    {
        return config('multidomain.namespace').'\\'.$this->name();
    }
}
