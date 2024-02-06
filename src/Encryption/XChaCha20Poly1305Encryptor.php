<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use BrosSquad\LaravelCrypto\Support\Base64;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

class XChaCha20Poly1305Encryptor extends SodiumEncryptor
{
    public const NONCE_SIZE = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;

    public function encrypt($value, $serialize = true): string
    {
        if ($serialize) {
            $value = $this->encoder->encode($value);
        }

        try {
            $nonce = $this->generateNonce();
            $encrypted = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
                $value,
                $nonce,
                $nonce,
                $this->keyLoader->getKey()
            );
            return Base64::urlEncodeNoPadding($nonce . $encrypted);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'value' => $value,
                'serialize' => $serialize,
            ]);
            throw new EncryptException('Value cannot be encrypted');
        }
    }

    public function decrypt($payload, $unserialize = true)
    {
        $decoded = Base64::urlDecode($payload);
        $nonce = substr($decoded, 0, self::NONCE_SIZE);
        $cipherText = substr($decoded, self::NONCE_SIZE, null);

        try {
            $decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
                $cipherText,
                $nonce,
                $nonce,
                $this->keyLoader->getKey()
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
                'value' => $value,
                'serialize' => $serialize,
            ]);
            throw new DecryptException('Payload cannot be decrypted');
        }

        if ($unserialize) {
            return $this->encoder->decode($decrypted);
        }

        return $decrypted;
    }

    public static function generateKey(string $cipher): string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_keygen();
    }
}
