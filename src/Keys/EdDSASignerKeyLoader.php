<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Exceptions\KeyPathNotFound;
use Illuminate\Contracts\Config\Repository;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SplFileObject;

class EdDSASignerKeyLoader implements Loader, Generator
{
    private const KEY_LENGTH = SODIUM_CRYPTO_SIGN_KEYPAIRBYTES;
    private const PUBLIC_KEY_LENGTH = SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES;
    private const PRIVATE_KEY_LENGTH = SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

    private readonly string $privateKey;
    private readonly string $publicKey;

    public function __construct(
        public readonly string $keyPath,
        private readonly LoggerInterface $logger,
    ) {
        [$this->publicKey, $this->privateKey] = $this->parseKeys();
    }

    private function parseKeys(): array
    {
        $file = new SplFileObject($this->keyPath, 'rb');
        if ($file->flock(LOCK_SH) === false) {
            throw new RuntimeException('Error while locking file (shared/reading)');
        }

        try {
            $keys = $file->fread(self::KEY_LENGTH);

            if ($keys === false) {
                throw new RuntimeException('Error while reading key');
            }
        } finally {
            if ($file->flock(LOCK_UN) === false) {
                $this->logger->warning('Error while unlocking file');
            }
        }

        $publicKey = substr($keys, 0, self::PUBLIC_KEY_LENGTH);
        $privateKey = substr($keys, self::PUBLIC_KEY_LENGTH, self::PRIVATE_KEY_LENGTH);

        return [$publicKey, $privateKey];
    }

    public function getKey(): string|array
    {
        return [$this->publicKey, $this->privateKey];
    }

    public function generate(): void
    {
        $keyPair = sodium_crypto_sign_keypair();
        $privateKey = sodium_crypto_sign_secretkey($keyPair);
        $publicKey = sodium_crypto_sign_publickey($keyPair);

        $file = new SplFileObject($this->keyPath, 'wb');

        if ($file->flock(LOCK_EX) === false) {
            throw new RuntimeException('Error while locking file (exclusive/writing)');
        }

        try {
            if ($file->fwrite($publicKey . $privateKey) === false) {
                throw new RuntimeException('Error while writing public key to file');
            }
        } finally {
            if ($file->flock(LOCK_UN) === false) {
                $this->logger->warning('Error while unlocking file');
            }
        }

        if ($file->fwrite($publicKey . $privateKey) === false) {
            throw new RuntimeException('Error while writing public key to file');
        }

        sodium_memzero($privateKey);
        sodium_memzero($publicKey);
        sodium_memzero($keyPair);
    }

    public function __destruct()
    {
        sodium_memzero($this->privateKey);
        sodium_memzero($this->publicKey);
    }
}