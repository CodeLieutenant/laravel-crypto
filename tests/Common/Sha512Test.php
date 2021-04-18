<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Common;

use BrosSquad\LaravelCrypto\Hashing\Sha512;
use BrosSquad\LaravelCrypto\Tests\TestCase;

class Sha512Test extends TestCase
{
    protected Sha512 $sha512;

    public function setUp(): void
    {
        parent::setUp();
        $this->sha512 = new Sha512();
    }

    public function test_hash(): void
    {
        $hashedExpected = hash('sha3-512', 'Hello World');
        $hashed = $this->sha512->hash('Hello World');
        self::assertIsString($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }

    public function test_hash_raw(): void
    {
        $hashedExpected = hash('sha3-512', 'Hello World', true);
        $hashed = $this->sha512->hashRaw('Hello World');
        self::assertIsBinary($hashed);
        self::assertEquals($hashedExpected, $hashed);
    }


    public function test_verify(): void
    {
        $hashedExpected = hash('sha3-512', 'Hello World');
        self::assertTrue($this->sha512->verify($hashedExpected, 'Hello World'));
    }

    public function test_verify_raw(): void
    {
        $hashedExpected = hash('sha3-512', 'Hello World');
        self::assertTrue($this->sha512->verify($hashedExpected, 'Hello World'));
    }
}
