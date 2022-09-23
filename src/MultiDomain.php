<?php

namespace Foxws\MultiDomain;

class MultiDomain
{
    public function __construct(
        protected MultiDomainRepository $repository,
    ) {
        //
    }

    public function initialize(): void
    {
        $this->repository->enabled();
    }
}
