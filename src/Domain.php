<?php

namespace Foxws\LaravelMultidomain;

use Foxws\LaravelMultidomain\Support\BaseDomain;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class Domain extends BaseDomain
{
    public function registerWebRoutes(): void
    {
        Route::group([
            'as' => "{$this->getNamespace()}.",
            'domain' => $this->getDomain(),
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom("{$this->getPath()}/Routes/web.php");
        });
    }

    public function registerApiRoutes(): void
    {
        Route::group([
            'as' => "{$this->getNamespace()}.",
            'domain' => $this->getDomain(),
            'prefix' => 'api',
            'middleware' => 'api',
        ], function () {
            $this->loadRoutesFrom("{$this->getPath()}/Routes/api.php");
        });
    }

    public function registerConfigs(): void
    {
        $paths = File::glob("{$this->getPath()}/Config/*.php");

        foreach ($paths as $path) {
            $name = pathinfo($path, PATHINFO_FILENAME);

            $this->mergeConfigFrom($path, "{$this->getNamespace()}.".strtolower($name));
        }
    }

    public function registerResources(): void
    {
        $this->loadTranslationsFrom("{$this->getPath()}/Resources/Translations");
        $this->loadViewsFrom("{$this->getPath()}/Resources/Views");
    }

    public function registerProviders(): void
    {
        $config = Config::get("{$this->getNamespace()}.app.providers", []);

        $providers = collect($config);
        $providers->each(fn (string $provider) => $this->app->register($provider));
    }

    protected function mergeConfigFrom(string $path, string $key): void
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            Config::set($key, array_merge(
                require $path, Config::get($key, [])
            ));
        }
    }

    protected function loadRoutesFrom(string $path): void
    {
        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            require $path;
        }
    }

    protected function loadTranslationsFrom(string $path): void
    {
        $this->callAfterResolving('translator', function ($translator) use ($path) {
            $translator->addNamespace($this->getNamespace(), $path);
        });
    }

    protected function loadViewsFrom(string|array $path): void
    {
        $namespace = $this->getNamespace();

        $this->callAfterResolving('view', function ($view) use ($path, $namespace) {
            if (isset($this->app->config['view']['paths']) &&
                is_array($this->app->config['view']['paths'])) {
                foreach ($this->app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath.'/vendor/'.$namespace)) {
                        $view->addNamespace($namespace, $appPath);
                    }
                }
            }

            $view->addNamespace($namespace, $path);
        });
    }

    protected function callAfterResolving(string $name, callable $callback): void
    {
        $this->app->afterResolving($name, $callback);

        if ($this->app->resolved($name)) {
            $callback($this->app->make($name), $this->app);
        }
    }
}
