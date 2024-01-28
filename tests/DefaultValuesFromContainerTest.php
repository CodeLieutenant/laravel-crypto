<?php


namespace BrosSquad\LaravelCrypto\Tests;


use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Encryption\SodiumEncryptor;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use Illuminate\Contracts\Encryption\Encrypter;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Hashing\Sha256;
use BrosSquad\LaravelCrypto\Hashing\Sha512;
use BrosSquad\LaravelCrypto\Hashing\Blake2b;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Contracts\Hashing;
use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use Illuminate\Encryption\Encrypter as LaravelDefaultEncrypter;
use Illuminate\Support\Facades\Config;

class DefaultValuesFromContainerTest extends TestCase
{
    public function test_should_get_default_hashing_blake2b(): void
    {
        $hashing = $this->app->get(Hashing::class);
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Blake2b::class, $hashing);
    }

    public function test_should_get_blade2_hashing_blake2b(): void
    {
        $hashing = $this->app->get('blake2b');
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Blake2b::class, $hashing);
    }

    public function test_should_get_sha256_hashing(): void
    {
        $hashing = $this->app->get('sha256');
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Sha256::class, $hashing);
    }

    public function test_should_get_sha512_hashing(): void
    {
        $hashing = $this->app->get('sha512');
        self::assertNotNull($hashing);
        self::assertInstanceOf(Hashing::class, $hashing);
        self::assertInstanceOf(Sha512::class, $hashing);
    }

    public function test_should_get_default_hmac_sha256(): void
    {
        $hmac = $this->app->get(Signing::class);
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }

    public function test_should_get_hmac_sha512(): void
    {
        $hmac = $this->app->get('hmac512');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac512::class, $hmac);
    }

    public function test_should_get_hmac_sha256(): void
    {
        $hmac = $this->app->get('hmac256');
        self::assertNotNull($hmac);
        self::assertInstanceOf(Signing::class, $hmac);
        self::assertInstanceOf(Hmac256::class, $hmac);
    }

    public function test_should_get_default_public_key_signing_algorithm(): void
    {
        $path = sys_get_temp_dir() . '/laravel_hashing.key';

        EdDSA::generateKeys($path);

        $signing = $this->app->get(PublicKeySigning::class);
        self::assertNotNull($signing);
        self::assertInstanceOf(PublicKeySigning::class, $signing);
        self::assertInstanceOf(Signing::class, $signing);
        self::assertInstanceOf(EdDSA::class, $signing);

        unlink($path);
    }

    public function test_should_get_default_xchacha20_algorithm(): void
    {
        Config::set('app.cipher', SodiumEncryptor::XChaCha20Poly1305);
        $encrypter = $this->app->make(Encrypter::class);
        self::assertNotNull($encrypter);
        self::assertInstanceOf(XChaCha20Poly5Encryptor::class, $encrypter);
    }

    public function test_should_get_default_aes256gcm_algorithm(): void
    {
        Config::set('app.cipher', SodiumEncryptor::AES256GCM);
        $encrypter = $this->app->make(Encrypter::class);
        self::assertNotNull($encrypter);
        self::assertInstanceOf(AesGcm256Encryptor::class, $encrypter);
    }

    public function test_should_get_default_aes256cbc_algorithm(): void
    {
        Config::set('app.cipher', SodiumEncryptor::AES256CBC);
        $encrypter = $this->app->make(Encrypter::class);
        self::assertNotNull($encrypter);
        self::assertInstanceOf(LaravelDefaultEncrypter::class, $encrypter);
    }
}
