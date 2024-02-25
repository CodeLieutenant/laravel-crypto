<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use BrosSquad\LaravelCrypto\Encoder\Encoder;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Support\Base64;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Psr\Log\LoggerInterface;

final class XChaCha20Poly1305Encryptor
{
    use Crypto;

    public function __construct(
        private readonly Loader $keyLoader,
        private readonly LoggerInterface $logger,
        private readonly Encoder $encoder,
    ) {
    }

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
            return Base64::constantUrlEncodeNoPadding($nonce . $encrypted);
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
        $decoded = Base64::constantUrlDecodeNoPadding($payload);
        $nonce = substr($decoded, 0, self::nonceSize());
        $cipherText = substr($decoded, self::nonceSize(), null);

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

    public static function nonceSize(): int
    {
        return SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;
    }
}
