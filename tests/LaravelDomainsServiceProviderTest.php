<?php

namespace Foxws\LaravelMultidomain\Tests;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;

class LaravelDomainsServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function it_binds_domains_key_to_repository_class()
    {
        $this->assertInstanceOf(RepositoryInterface::class, app(RepositoryInterface::class));
        $this->assertInstanceOf(RepositoryInterface::class, app('domains'));
    }
}
