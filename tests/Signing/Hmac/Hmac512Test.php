<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Tests\Signing\Hmac;


use BrosSquad\LaravelCrypto\Signing\Hmac\Hmac256;
use Illuminate\Support\Str;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Support\Base64;

class Hmac512Test extends TestCase
{
    /** @var Hmac256 */
    protected $hmac512;
    protected $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hmac512 = $this->app->get('hmac512');
        $key = config('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = Str::substr($key, 7);
        }

        $this->key = Base64::decode($key);
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
