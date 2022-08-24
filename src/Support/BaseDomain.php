<?php

namespace Foxws\LaravelMultidomain\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;

abstract class BaseDomain
{
    use Macroable;

    public function __construct(
        protected Application $app,
        protected array $attributes
    ) {
    }

    public function boot(): void
    {
        $this->registerWebRoutes();
        $this->registerApiRoutes();
        $this->registerConfigs();
        $this->registerResources();
    }

    public function register(): void
    {
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function getEnabled(): bool
    {
        return $this->getAttribute('enabled');
    }

    public function getDomain(): string
    {
        return match ($this->app->environment()) {
            'production' => $this->getAttribute('domain.production'),
            'staging' => $this->getAttribute('domain.staging'),
            default => $this->getAttribute('domain.local'),
        };
    }

    public function getPath(): string
    {
        return config('multidomain.path').'/'.$this->getName();
    }

    public function getNamespace(): string
    {
        return strtolower($this->getAttribute('name'));
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return data_get($this->getAttributes(), $key, $default);
    }

    abstract public function registerWebRoutes(): void;

    abstract public function registerApiRoutes(): void;

    abstract public function registerConfigs(): void;

    abstract public function registerResources(): void;

    abstract public function registerProviders(): void;
}
