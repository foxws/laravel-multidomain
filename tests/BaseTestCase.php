<?php

namespace Foxws\LaravelMultidomain\Tests;

use Foxws\LaravelMultidomain\LaravelMultidomainServiceProvider;
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
            LaravelMultidomainServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
