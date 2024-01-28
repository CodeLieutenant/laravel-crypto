<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

enum Encryption: string
{
    case AES128CBC = 'AES-128-CBC';
    case AES256CBC = 'AES-256-CBC';
    case AES256GCM = 'AES-256-GCM';
    case XChaCha20Poly1305 = 'XChaCha20Poly1305';

    public function keySize(): int
    {
        return match ($this) {
            self::AES128CBC => 16,
            self::AES256CBC => 32,
            self::AES256GCM => SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES,
            self::XChaCha20Poly1305 => SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES,
        };
    }
}
