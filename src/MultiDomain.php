<?php

namespace Foxws\MultiDomain;

use Illuminate\Support\Collection;

class MultiDomain
{
    public function __construct(
        protected MultiDomainRepository $repository,
    ) {
    }

    public function boot(): void
    {
        $this->repository->build();
    }

    public function find(string $name): ?MultiDomain
    {
        $domains = $this->repository->all();

        return collect($domains)
            ->firstWhere('name', $name);
    }

    public function findByDomain(string $domain): Collection
    {
        $domains = $this->repository->all();

        return collect($domains)->filter(function ($item) use ($domain) {
            if (array_search($domain, $item->domain)) {
                return $domain;
            }
        });
    }
}
