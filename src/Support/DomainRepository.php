<?php

namespace Foxws\LaravelMultidomain\Support;

use Foxws\LaravelMultidomain\Domain;

class DomainRepository extends FileRepository
{
    protected function createDomain(...$args): Domain
    {
        return new Domain(...$args);
    }
}
