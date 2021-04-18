<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Signing\Hmac;

use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use BrosSquad\LaravelCrypto\Tests\TestCase;

class Hmac256Test extends TestCase
{
    protected Hmac256 $hmac256;

    public function setUp(): void
    {
        parent::setUp();

        $this->hmac256 = new Hmac256($this->key);
    }

    public function test_sign(): void
    {
        $expectedSignature = sodium_bin2base64(
            sodium_crypto_auth('Hello World', $this->key),
            SODIUM_BASE64_VARIANT_URLSAFE
        );
        $signature = $this->hmac256->sign('Hello World');

        self::assertIsString($signature);
        self::assertIsBase64Url($signature);
        self::assertEquals($expectedSignature, $signature);
    }

    public function test_sign_raw(): void
    {
        $expectedSignature = sodium_crypto_auth('Hello World', $this->key);
        $signature = $this->hmac256->signRaw('Hello World');
        self::assertIsBinary($signature);
        self::assertEquals($expectedSignature, $signature);
    }
}
