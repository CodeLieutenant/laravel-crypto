<?php


namespace BrosSquad\LaravelCrypto\Tests;


use BrosSquad\LaravelCrypto\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Common\Sha256;
use BrosSquad\LaravelCrypto\Common\Sha512;
use BrosSquad\LaravelCrypto\Common\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Hmac;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

class DefaultValuesFromContainerTest extends TestCase
{
    /** @test */
    public function should_get_default_hashing_blase2(): void
    {
        $hashing = $this->app->get(Hashing::class);
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Blake2b::class, $hashing);
    }

    /** @test */
    public function should_get_blade2_hashing_blase2(): void
    {
        $hashing = $this->app->get(Hashing::class);
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Blake2b::class, $hashing);
    }

    /** @test */
    public function should_get_sha256_hashing(): void
    {
        $hashing = $this->app->get('sha256');
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Sha256::class, $hashing);
    }

    /** @test */
    public function should_get_sha512_hashing(): void
    {
        $hashing = $this->app->get('sha512');
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Sha512::class, $hashing);
    }

    /** @test */
    public function should_get_default_hmac_sha256(): void
    {
        $hmac = $this->app->get(Hmac::class);
        self::assertNotNull($hmac);
        self::assertInstanceOf(Hmac::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }

    /** @test */
    public function should_get_hmac_sha512(): void
    {
        $hmac = $this->app->get('hmac512');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Hmac::class, $hmac);
        self::assertInstanceOf(Hmac512::class, $hmac);
    }

    /** @test */
    public function should_get_hmac_sha256(): void
    {
        $hmac = $this->app->get('hmac256');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Hmac::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }
}
