<?php

namespace Foxws\MultiDomain\Support;

use Foxws\MultiDomain\Domains\Domain\MultiDomain;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class MultiDomainFinder
{
    public function __construct(
        protected CacheRepository $cache,
        protected ConfigRepository $config,
        protected Filesystem $files,
    ) {
    }

    public function domains(): array
    {
        if (! $this->config->get('multidomain.cache_enabled')) {
            return $this->scan();
        }

        return $this->format($this->cached());
    }

    protected function scan(): array
    {
        $path = namespace_path(
            $this->config->get('multidomain.namespace')
        );

        $manifests = $this->files->glob("{$path}*/domain.json");

        $domains = [];

        foreach ($manifests as $manifest) {
            $attributes = json_decode($this->files->get($manifest), true);

            throw_if(json_last_error() > 0, RuntimeException::class, json_last_error_msg());

            $domains[] = MultiDomain::new()->attributes($attributes);
        }

        return $domains;
    }

    protected function cached(): array
    {
        $ttl = $this->config->get('multidomain.cache_lifetime');

        return $this->cache->remember('multidomain', $ttl, function () {
            return $this->toArray();
        });
    }

    protected function format(array $cached): array
    {
        $domains = [];

        foreach ($cached as $domain) {
            $domains[] = MultiDomain::new()->attributes($domain);
        }

        return $domains;
    }

    protected function toArray(): array
    {
        return collect($this->scan())
            ->map(fn (MultiDomain $domain) => $domain->toArray())
            ->toArray();
    }
}
