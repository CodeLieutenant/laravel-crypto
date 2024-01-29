<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encryption;

use BrosSquad\LaravelCrypto\Encoder\Encoder;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Support\Random;
use Illuminate\Contracts\Encryption\Encrypter;
use BrosSquad\LaravelCrypto\Contracts\KeyGeneration;
use Illuminate\Contracts\Encryption\StringEncrypter;
use Illuminate\Encryption\Encrypter as LaravelEncrypter;
use Psr\Log\LoggerInterface;

abstract class SodiumEncryptor implements Encrypter, KeyGeneration, StringEncrypter
{
    public const NONCE_SIZE = 0;

    public function __construct(
        protected readonly Loader $keyLoader,
        protected readonly LoggerInterface $logger,
        protected readonly Encoder $encoder,
    ) {
    }

    public function getKey(): string
    {
        return $this->keyLoader->getKey();
    }

    public static function supported(string $key, string $cipher): bool
    {
        $encType = Encryption::tryFrom($cipher);

        if ($encType === null) {
            return LaravelEncrypter::supported($key, $cipher);
        }

        if ($encType === Encryption::SodiumAES256GCM && !sodium_crypto_aead_aes256gcm_is_available()) {
            return false;
        }

        return strlen($key) === $encType->keySize();
    }

    public function encryptString($value): string
    {
        return $this->encrypt($value, false);
    }

    public function decryptString($payload): string
    {
        return $this->decrypt($payload, false);
    }

    protected function generateNonce(?string $previous = null): string
    {
        if ($previous !== null) {
            $copy = $previous;
            sodium_increment($copy);
            return $copy;
        }

        return Random::bytes(static::NONCE_SIZE);
    }

}
