<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests;

use BrosSquad\LaravelCrypto\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use BrosSquad\LaravelCrypto\Facades\{
    Signing,
    Hashing
};

class TestCase extends OrchestraTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            "Hashing" => Hashing::class,
            "Signing" => Signing::class,
        ];
    }

    public static function assertIsBase64(string $text, string $message = ''): void
    {
        static::assertThat(preg_match('#^([a-zA-Z0-9/+]+)={0,2}$#', $text) === 1, static::isTrue(), $message);
    }

    public static function assertIsBase64Url(string $text, string $message = ''): void
    {
        static::assertThat(preg_match('#^([a-zA-Z0-9_-]+)={0,2}$#', $text) === 1, static::isTrue(), $message);
    }

    public static function assertIsBase64NoPadding(string $text, string $message = ''): void
    {
        static::assertThat(preg_match('#^[a-zA-Z0-9/-]+$#', $text) === 1, static::isTrue(), $message);
    }

    public static function assertIsBase64UrlNoPadding(string $text, string $message = ''): void
    {
        static::assertThat(preg_match('#^[a-zA-Z0-9_-]+$#', $text) === 1, static::isTrue(), $message);
    }
}
