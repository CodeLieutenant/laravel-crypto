<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            \BrosSquad\LaravelCrypto\ServiceProvider::class,
        ];
    }
}
