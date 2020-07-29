<?php


namespace BrosSquad\LaravelCrypto\Tests;

use BrosSquad\LaravelCrypto\HashingServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use BrosSquad\LaravelCrypto\Facades\{
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

    public static function assertIsBinary(string $text, string $message = ''): void
    {
        static::assertThat(ctype_print($text), static::isFalse(), $message);
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
