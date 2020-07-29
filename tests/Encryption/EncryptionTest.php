<?php


namespace BrosSquad\LaravelCrypto\Tests\Encryption;


use Exception;
use Illuminate\Support\Facades\Config;
use BrosSquad\LaravelCrypto\Tests\TestCase;

class EncryptionTest extends TestCase
{

    public function test_not_supported_algorithms(): void
    {
        $this->expectException(Exception::class);
        Config::set('app.cipher', 'unsupported algorithm');
        $this->app->get('encrypter');
    }
}
