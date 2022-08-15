<?php

namespace Foxws\LaravelMultidomain\Support;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Foxws\LaravelMultidomain\Domain;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Traits\Macroable;
use RuntimeException;

abstract class FileRepository implements RepositoryInterface
{
    use Macroable;

    public function __construct(
        protected Application $app,
    ) {
    }

    public function register(): void
    {
    }

    public function boot(): void
    {
        foreach ($this->allEnabled() as $domain) {
            $domain->boot();
        }
    }

    public function allEnabled(): array
    {
        return Collection::make($this->all())
            ->filter(fn (Domain $domain) => $domain->getEnabled() === true)
            ->all();
    }

    public function allDisabled(): array
    {
        return Collection::make($this->all())
            ->filter(fn (Domain $domain) => $domain->getEnabled() === false)
            ->all();
    }

    public function all(): array
    {
        if (! $this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    public function getCached(): array
    {
        return Cache::remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    public function removeCached(): void
    {
        Cache::forget($this->config('cache.key'));
    }

    public function toCollection(): Collection
    {
        return Collection::make($this->scan())
            ->map(fn (Domain $domain) => $domain->getAttributes());
    }

    public function find(string $name): ?Domain
    {
        foreach ($this->all() as $module) {
            if ($module->getName() === $name) {
                return $module;
            }
        }

        return null;
    }

    public function findOrFail(string $name): Domain
    {
        $domain = $this->find($name);

        return throw_if(! $domain);
    }

    abstract protected function createDomain(...$args): Domain;

    protected function scan(): array
    {
        $paths = $this->getScanPaths();

        $domains = [];

        foreach ($paths as $path) {
            $manifests = File::glob("{$path}/domain.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $attributes = json_decode($this->getContents($manifest), true);

                throw_if(json_last_error() > 0, RuntimeException::class, json_last_error_msg());

                $domains[] = $this->createDomain($this->app, $attributes);
            }
        }

        return $domains;
    }

    protected function getScanPaths(): array
    {
        if (! $this->config('scan.enabled')) {
            return [];
        }

        return $this->config('scan.paths');
    }

    protected function formatCached(array $cached): array
    {
        $domains = [];

        foreach ($cached as $domain) {
            $domains[] = $this->createDomain($this->app, $domain);
        }

        return $domains;
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return Config::get('multidomain.'.$key, $default);
    }

    protected function getContents(string $path): string
    {
        return File::get($path);
    }
}
