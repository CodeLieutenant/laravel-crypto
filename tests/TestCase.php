<?php


namespace BrosSquad\LaravelHashing\Tests;

use BrosSquad\LaravelHashing\HashingServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use BrosSquad\LaravelHashing\Facades\{
    Hmac,
    Base64,
    Random,
    Hashing
};

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [HashingServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            "Hashing" => Hashing::class,
            "Base64"  => Base64::class,
            "Hmac"    => Hmac::class,
            "Random"  => Random::class,
        ];
    }
}
