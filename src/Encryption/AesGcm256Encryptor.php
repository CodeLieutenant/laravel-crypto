<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use BrosSquad\LaravelCrypto\Support\Base64;
use Exception;

class AesGcm256Encryptor extends SodiumEncryptor
{
    public const NONCE_SIZE = SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES;

    public function encrypt($value, $serialize = true): string
    {
        try {
            $nonce = random_bytes(self::NONCE_SIZE);

            if ($serialize) {
                $value = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $encrypted = sodium_crypto_aead_aes256gcm_encrypt($value, $nonce, $nonce, $this->key);
            return Base64::urlEncodeNoPadding($nonce . $encrypted);
        } catch (Exception $e) {
            throw new EncryptException('Value cannot be encrypted');
        }
    }

    public function decrypt($payload, $unserialize = true)
    {
        $decoded = Base64::urlDecode($payload);
        $nonce = mb_substr($decoded, 0, self::NONCE_SIZE, '8bit');
        $cipherText = mb_substr($decoded, self::NONCE_SIZE, null, '8bit');

        try {
            $decrypted = sodium_crypto_aead_aes256gcm_decrypt($cipherText, $nonce, $nonce, $this->key);

            if ($unserialize) {
                return json_decode(
                    $decrypted,
                    true,
                    512,
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_OBJECT_AS_ARRAY
                );
            }

            return $decrypted;
        } catch (Exception $e) {
            throw new DecryptException('Payload cannot be decrypted');
        }
    }

    public static function generateKey(): string
    {
        return sodium_crypto_aead_aes256gcm_keygen();
    }
}
