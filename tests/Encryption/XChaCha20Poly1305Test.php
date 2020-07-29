<?php


namespace BrosSquad\LaravelCrypto\Tests\Encryption;


use Illuminate\Support\Str;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use BrosSquad\LaravelCrypto\Facades\Base64;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;

class XChaCha20Poly1305Test extends TestCase
{
    protected $encrypter;

    protected function setUp(): void
    {
        parent::setUp();
        $key = config('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = Base64::decode(Str::after($key, 'base64:'));
        }
        $this->encrypter = new XChaCha20Poly5Encryptor($key);
    }

    public function testEncryption(): void
    {
        $encrypted = $this->encrypter->encrypt('foo');
        self::assertNotSame('foo', $encrypted);
        self::assertSame('foo', $this->encrypter->decrypt($encrypted));
    }

    public function test_key_length(): void {
        $this->expectException(\RuntimeException::class);
        $this->expectDeprecationMessage('XChaCha20-Poly1305 key has to be 32 bytes in length');
        new XChaCha20Poly5Encryptor(str_repeat('a', 16));
    }
}
