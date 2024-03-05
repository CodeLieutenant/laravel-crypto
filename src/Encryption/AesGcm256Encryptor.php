<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Encryption;

use CodeLieutenant\LaravelCrypto\Contracts\KeyGeneration;
use CodeLieutenant\LaravelCrypto\Encoder\Encoder;
use CodeLieutenant\LaravelCrypto\Encoder\JsonEncoder;
use CodeLieutenant\LaravelCrypto\Keys\Loader;
use CodeLieutenant\LaravelCrypto\Support\Base64;
use CodeLieutenant\LaravelCrypto\Traits\Crypto;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\StringEncrypter;
use Psr\Log\LoggerInterface;

final class AesGcm256Encryptor implements Encrypter, KeyGeneration, StringEncrypter
{
    use Crypto;

    public function __construct(
        private readonly Loader $keyLoader,
        private readonly Encoder $encoder = new JsonEncoder(),
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function encrypt($value, $serialize = true): string
    {
        $serialized = match ($serialize) {
            true => $this->encoder->encode($value),
            false => $value,
        };

        try {
            $nonce = $this->generateNonce();
            $encrypted = sodium_crypto_aead_aes256gcm_encrypt($serialized, $nonce, $nonce, $this->getKey());
            return Base64::constantUrlEncodeNoPadding($nonce . $encrypted);
        } catch (Exception $e) {
            $this->logger?->error($e->getMessage(), [
                'exception' => $e,
                'stack' => $e->getTraceAsString(),
            ]);
            throw new EncryptException('Value cannot be encrypted');
        }
    }

    public function decrypt($payload, $unserialize = true)
    {
        $decoded = Base64::constantUrlDecodeNopadding($payload);
        $nonce = substr($decoded, 0, self::nonceSize());
        $cipherText = substr($decoded, self::nonceSize());

        try {
            $decrypted = sodium_crypto_aead_aes256gcm_decrypt($cipherText, $nonce, $nonce, $this->keyLoader->getKey());
        } catch (Exception $e) {
            $this->logger?->error($e->getMessage(), [
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

    public static function nonceSize(): int
    {
        return SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES;
    }
}
