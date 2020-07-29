<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelHashing\Tests\Common;

use BrosSquad\LaravelHashing\Tests\TestCase;
use BrosSquad\LaravelHashing\Common\Blake2b;

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
}
