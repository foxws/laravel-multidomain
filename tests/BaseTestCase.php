<?php

namespace Foxws\LaravelMultidomain\Tests;

use Foxws\MultiDomain\MultiDomainServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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
