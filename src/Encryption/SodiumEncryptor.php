<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use Illuminate\Contracts\Encryption\Encrypter;
use BrosSquad\LaravelCrypto\Contracts\KeyGeneration;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\StringEncrypter;
use Illuminate\Encryption\Encrypter as LaravelEncrypter;
use Illuminate\Support\Str;

abstract class SodiumEncryptor implements Encrypter, KeyGeneration, StringEncrypter
{
    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public static function supported(string $key, Encryption $cipher): bool
    {
        if (Str::startsWith($key, 'base64:')) {
            $key = Str::after($key, 'base64:');
            $key = base64_decode($key);
        }

        if ($cipher === Encryption::AES256GCM && !sodium_crypto_aead_aes256gcm_is_available()) {
            return false;
        }

        return mb_strlen($key, '8bit') === $cipher->keySize();

        return LaravelEncrypter::supported($key, $cipher);
    }

    public function encryptString($value): string
    {
        return $this->encrypt($value, false);
    }

    public function decryptString($payload): string
    {
        return $this->decrypt($payload, false);
    }


    public function __destruct()
    {
        sodium_memzero($this->key);
    }
}
