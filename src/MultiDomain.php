<?php

namespace Foxws\MultiDomain;

use Foxws\MultiDomain\Providers\DomainServiceProvider;
use Foxws\MultiDomain\Support\Domain;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class MultiDomain
{
    public function __construct(
        protected Application $app,
        protected CacheRepository $cache,
        protected ConfigRepository $config,
        protected Filesystem $files,
    ) {
    }

    public function initialize(string $domain): void
    {
        $domain = $this->findByDomain($domain);

        $provider = $this->app->make(DomainServiceProvider::class, compact('domain'));

        $provider->register();
        $provider->boot();
    }

    protected function findByDomain(string $domain): Domain
    {
        $domains = $this->config->get('multidomain.cache_enabled', false)
            ? $this->cached()
            : $this->all();

        return $domains
            ->each(fn (Domain $item) => $item->domain = $item->domain[$this->app->environment()] ?? null)
            ->firstOrFail(fn (Domain $item) => $item->domain === $domain);
    }

    protected function all(): Collection
    {
        $path = $this->path(
            $this->config->get('multidomain.namespace', 'App\\Domain')
        );

        $manifests = $this->files->glob("{$path}*/domain.json");

        $domains = collect();

        foreach ($manifests as $manifest) {
            $attributes = json_decode($this->files->get($manifest), true);

            throw_if(json_last_error() > 0, RuntimeException::class, json_last_error_msg());

            $domain = new Domain($attributes);
            $domain->manifest = $manifest;

            $domains->push($domain);
        }

        return $domains;
    }

    protected function cached(): Collection
    {
        $ttl = this->config->get('multidomain.cache_lifetime', 3600);

        return $this->cache->remember('multidomain', $ttl, fn () => $this->all());
    }

    protected function path(string $path): string
    {
        return str($path)
            ->finish('\\')
            ->replaceFirst($this->app->getNamespace(), '/')
            ->replace('\\', '/')
            ->prepend($this->app->path);
    }
}
