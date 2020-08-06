<?php


namespace BrosSquad\LaravelCrypto\Tests\Encryption;


use BrosSquad\LaravelCrypto\Encryption\SodiumEncryptor;
use Exception;
use Illuminate\Support\Facades\Config;
use BrosSquad\LaravelCrypto\Tests\TestCase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptionTest extends TestCase
{

    public function test_not_supported_algorithms(): void
    {
        $this->expectException(Exception::class);
        Config::set('app.cipher', 'unsupported algorithm');
        $this->app->get('encrypter');
    }

    public function test_supported_algorithms(): void
    {
        $key = Config::get('app.key');
        self::assertTrue(SodiumEncryptor::supported($key, SodiumEncryptor::AES256CBC));
        if (sodium_crypto_aead_aes256gcm_is_available()) {
            self::assertTrue(SodiumEncryptor::supported($key, SodiumEncryptor::AES256GCM));
        }
        self::assertTrue(SodiumEncryptor::supported($key, SodiumEncryptor::XChaCha20Poly1305));
        self::assertFalse(SodiumEncryptor::supported($key, SodiumEncryptor::AES128CBC));
        self::assertTrue(SodiumEncryptor::supported(random_bytes(16), SodiumEncryptor::AES128CBC));
    }


    public function test_supported_algorithms_with_crypt_facade(): void
    {
        Config::set('app.cipher', SodiumEncryptor::XChaCha20Poly1305);
        $key = Config::get('app.key');
        self::assertTrue(Crypt::supported($key, SodiumEncryptor::AES256CBC));
        if (sodium_crypto_aead_aes256gcm_is_available()) {
            self::assertTrue(Crypt::supported($key, SodiumEncryptor::AES256GCM));
        }
        self::assertTrue(Crypt::supported($key, SodiumEncryptor::XChaCha20Poly1305));
        self::assertFalse(Crypt::supported($key, SodiumEncryptor::AES128CBC));
        self::assertTrue(Crypt::supported(random_bytes(16), SodiumEncryptor::AES128CBC));
    }



    public function test_get_key_with_sodium(): void
    {
        $configKey = base64_decode(Str::after(Config::get('app.key'), 'base64:'));

        // Algorithm does not matter as long as inherits SodiumEncryptor base class
        Config::set('app.cipher', SodiumEncryptor::XChaCha20Poly1305);

        $key = Crypt::getKey();

        self::assertSame($configKey, $key);
    }
}
