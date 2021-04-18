<?php

declare(strict_types=1);

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

        $length = mb_strlen($key, '8bit');
        if ($length !== SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES) {
            throw new RuntimeException(sprintf('AES-256-GCM key has to be %d bytes in length', SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES));
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
