<?php


namespace BrosSquad\LaravelCrypto\Tests;


use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSAManager;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Common\Sha256;
use BrosSquad\LaravelCrypto\Common\Sha512;
use BrosSquad\LaravelCrypto\Common\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use Illuminate\Support\Facades\Config;

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
        $hmac = $this->app->get(Signing::class);
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }

    /** @test */
    public function should_get_hmac_sha512(): void
    {
        $hmac = $this->app->get('hmac512');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac512::class, $hmac);
    }

    /** @test */
    public function should_get_hmac_sha256(): void
    {
        $hmac = $this->app->get('hmac256');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }

    /** @test */
    public function should_get_default_public_key_signing_algorithm(): void
    {
        Config::set('crypto.public_key', sys_get_temp_dir() . '/laravel_hashing_crypto_public.key');
        Config::set('crypto.private_key', sys_get_temp_dir() . '/laravel_hashing_crypto_private.key');

        EdDSAManager::generateKeys(
            Config::get('crypto.private_key'),
            Config::get('crypto.public_key')
        );
        $signing = $this->app->get(PublicKeySigning::class);
        self::assertNotNull($signing);
        self::assertInstanceOf(PublicKeySigning::class, $signing);
        self::assertInstanceOf(Signing::class, $signing);
        self::assertInstanceOf(EdDSAManager::class, $signing);

        unlink(Config::get('crypto.public_key'));
        unlink(Config::get('crypto.private_key'));
    }
}
