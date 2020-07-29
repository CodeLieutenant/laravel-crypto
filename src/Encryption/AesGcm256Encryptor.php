<?php


namespace BrosSquad\LaravelCrypto\Encryption;


use RuntimeException;

class AesGcm256Encryptor extends SodiumEncryptor
{
    public function __construct(string $key)
    {
        parent::__construct($key);
        if (!sodium_crypto_aead_aes256gcm_is_available()) {
            throw new RuntimeException('AES-256-GCM is not available on your processor');
        }
    }

    public function encrypt($value, $serialize = true): string
    {
        return $this->encryptRaw(
            $value,
            $serialize,
            SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES,
            'sodium_crypto_aead_aes256gcm_encrypt'
        );
    }

    public function decrypt($payload, $unserialize = true)
    {
        return $this->decryptRaw(
            $payload,
            $unserialize,
            SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES,
            'sodium_crypto_aead_aes256gcm_decrypt'
        );
    }

    public static function generateKey(): string
    {
        return sodium_crypto_aead_aes256gcm_keygen();
    }
}
