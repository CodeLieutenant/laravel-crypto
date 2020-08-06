<?php


namespace BrosSquad\LaravelCrypto\Encryption;


use RuntimeException;

class XChaCha20Poly5Encryptor extends SodiumEncryptor
{
    public function __construct(string $key)
    {
        parent::__construct($key);

        $length = mb_strlen($key, '8bit');
        if ($length !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
            throw new RuntimeException('XChaCha20-Poly1305 key has to be 32 bytes in length');
        }
    }

    public function encrypt($value, $serialize = true): string
    {
        return $this->encryptRaw(
            $value,
            $serialize,
            SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES,
            'sodium_crypto_aead_xchacha20poly1305_ietf_encrypt'
        );
    }

    public function decrypt($payload, $unserialize = true)
    {
        return $this->decryptRaw(
            $payload,
            $unserialize,
            SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES,
            'sodium_crypto_aead_xchacha20poly1305_ietf_decrypt'
        );
    }

    public static function generateKey(): string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_keygen();
    }
}
