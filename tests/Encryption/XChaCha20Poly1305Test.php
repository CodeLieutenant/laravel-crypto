<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests\Encryption;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use RuntimeException;

class XChaCha20Poly1305Test extends TestCase
{
    protected XChaCha20Poly5Encryptor $encrypter;

    public function setUp(): void
    {
        parent::setUp();
        $this->encrypter = new XChaCha20Poly5Encryptor($this->key);
    }

    public function testEncryption(): void
    {
        $encrypted = $this->encrypter->encrypt('foo');
        self::assertNotSame('foo', $encrypted);
        self::assertSame('foo', $this->encrypter->decrypt($encrypted));
    }

    public function test_key_length(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectDeprecationMessage('XChaCha20-Poly1305 key has to be 32 bytes in length');
        new XChaCha20Poly5Encryptor(str_repeat('a', 16));
    }

    public function test_xchacha20_with_laravel_encryption_facade(): void
    {
        Config::set('app.cipher', 'XChaCha20Poly1305');
        self::assertInstanceOf(XChaCha20Poly5Encryptor::class, $this->app->make(Encrypter::class));
        $cipherText = Crypt::encrypt('Hello World');
        $cipherData = Crypt::encrypt(['hello' => 'World']);
        $cipherTextWithString = Crypt::encryptString('This is string');
        self::assertNotSame('Hello World', $cipherText);
        self::assertSame('Hello World', Crypt::decrypt($cipherText));
        self::assertNotSame(['hello' => 'World'], $cipherData);
        self::assertSame(['hello' => 'World'], Crypt::decrypt($cipherData));
        self::assertNotSame('This is string', $cipherTextWithString);
        self::assertSame('This is string', Crypt::decryptString($cipherTextWithString));
    }
}
