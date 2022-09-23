<?php

namespace Foxws\MultiDomain;

use Illuminate\Cache\Repository;

class MultiDomainRepository
{
    public function __construct(
        protected Repository $cache,
    ) {
    }

    public function enabled(): void
    {
        dd('scan');
    }
}
