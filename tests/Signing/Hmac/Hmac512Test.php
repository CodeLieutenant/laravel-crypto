<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Signing\Hmac;

use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac512;
use BrosSquad\LaravelCrypto\Tests\TestCase;

class Hmac512Test extends TestCase
{
    protected Hmac512 $hmac512;

    public function setUp(): void
    {
        parent::setUp();
        $this->hmac512 = new Hmac512($this->key);
    }

    public function test_sign(): void
    {
        $expectedSignature = hash_hmac('sha3-512', 'Hello World', $this->key);
        $signature = $this->hmac512->sign('Hello World');

        self::assertIsString($signature);
        self::assertEquals($expectedSignature, $signature);
    }

    public function test_sign_raw(): void
    {
        $expectedSignature = hash_hmac('sha3-512', 'Hello World', $this->key, true);
        $signature = $this->hmac512->signRaw('Hello World');
        self::assertIsBinary($signature);
        self::assertEquals($expectedSignature, $signature);
    }
}
