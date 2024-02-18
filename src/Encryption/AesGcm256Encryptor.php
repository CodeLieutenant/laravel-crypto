<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use BrosSquad\LaravelCrypto\Support\Base64;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

class AesGcm256Encryptor extends SodiumEncryptor
{
    public const NONCE_SIZE = SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES;

    public function encrypt($value, $serialize = true): string
    {
        $serialized = match ($serialize) {
            true => $this->encoder->encode($value),
            false => $value,
        };

        try {
            $nonce = $this->generateNonce();
            $encrypted = sodium_crypto_aead_aes256gcm_encrypt($serialized, $nonce, $nonce, $this->getKey());
            return Base64::urlEncodeNoPadding($nonce . $encrypted);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'stack' => $e->getTraceAsString(),
            ]);
            throw new EncryptException('Value cannot be encrypted');
        }
    }

    public function decrypt($payload, $unserialize = true)
    {
        $decoded = Base64::urlDecode($payload);
        $nonce = substr($decoded, 0, self::NONCE_SIZE);
        $cipherText = substr($decoded, self::NONCE_SIZE);

        try {
            $decrypted = sodium_crypto_aead_aes256gcm_decrypt($cipherText, $nonce, $nonce, $this->keyLoader->getKey());
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'exception' => $e,
            ]);
            throw new DecryptException('Payload cannot be decrypted');
        }

        return match ($unserialize) {
            true => $this->encoder->decode($decrypted),
            false => $decrypted,
        };
    }

    public static function generateKey(string $cipher): string
    {
        return sodium_crypto_aead_aes256gcm_keygen();
    }
}
