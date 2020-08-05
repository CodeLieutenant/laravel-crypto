<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Common;

use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Common\Blake2b;

class Blake2bTest extends TestCase
{
    protected $blake2b;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blake2b = new Blake2b();
    }

    public function test_hash(): void
    {
        $hashedExpected = sodium_bin2base64(
            sodium_crypto_generichash('Hello World', '', 64),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );
        $hashed = $this->blake2b->hash('Hello World');
        self::assertIsString($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }

    public function test_hash_raw(): void
    {
        $hashedExpected = sodium_crypto_generichash('Hello World', '', 64);
        $hashed = $this->blake2b->hashRaw('Hello World');
        self::assertIsBinary($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }

    public function test_verify(): void
    {
        $data = 'Hello World';
        $hash = sodium_bin2base64(
            sodium_crypto_generichash($data, '', 64),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );

        self::assertTrue($this->blake2b->verify($hash, $data));
    }

    public function test_verify_with_incorrect_data(): void
    {
        $data = 'Hello World';
        $hash = sodium_bin2base64(
            sodium_crypto_generichash($data, '', 64),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );

        self::assertFalse($this->blake2b->verify($hash, 'Incorrect Data'));
    }

    public function test_verify_with_incorrect_hash(): void
    {
        $data = 'Hello World';
        $hash = sodium_bin2base64(
            sodium_crypto_generichash($data, '', 64),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );
        $incorrectHash = sodium_bin2base64(
            sodium_crypto_generichash('Incorrect Data', '', 64),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );

        self::assertFalse($this->blake2b->verify($incorrectHash, 'Hello World'));
    }

    public function test_verify_raw(): void
    {
        $data = 'Hello World';
        $hash = sodium_crypto_generichash($data, '', 64);
        self::assertTrue($this->blake2b->verifyRaw($hash, $data));
    }

    public function test_verify_raw_with_incorrect_data(): void
    {
        $data = 'Hello World';
        $hash = sodium_crypto_generichash($data, '', 64);
        self::assertFalse($this->blake2b->verifyRaw($hash, 'Incorrect Data'));
    }


}
