<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Tests\Hmac;


use Illuminate\Support\Str;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Facades\Base64;

class Hmac256Test extends TestCase
{
    /** @var \BrosSquad\LaravelCrypto\Hmac\Hmac256 */
    protected $hmac256;
    protected $keyBinary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hmac256 = $this->app->get('hmac256');
        $key = config('app.key');
        if (($isBase64Encoded = Str::startsWith($key, 'base64:'))) {
            $key = Str::substr($key, 7);
        }

        $this->keyBinary = $isBase64Encoded ? Base64::decode($key) : hex2bin($key);
    }

    public function test_sign(): void
    {
        $expectedSignature = sodium_bin2base64(
            sodium_crypto_auth('Hello World', $this->keyBinary),
            SODIUM_BASE64_VARIANT_URLSAFE
        );
        $signature = $this->hmac256->sign('Hello World');

        self::assertIsString($signature);
        self::assertIsBase64Url($signature);
        self::assertEquals($expectedSignature, $signature);
    }

    public function test_sign_raw(): void
    {
        $expectedSignature = sodium_crypto_auth('Hello World', $this->keyBinary);
        $signature = $this->hmac256->signRaw('Hello World');
        self::assertIsBinary($signature);
        self::assertEquals($expectedSignature, $signature);
    }
}
