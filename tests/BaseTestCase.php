<?php

namespace Foxws\LaravelMultidomain\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Foxws\MultiDomain\MultiDomainServiceProvider;

abstract class BaseTestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            MultiDomainServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
