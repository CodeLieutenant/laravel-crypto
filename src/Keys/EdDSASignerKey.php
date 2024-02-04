<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\MissingAppKeyException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SplFileObject;

class EdDSASignerKey implements Loader, Generator
{
    private const KEY_LENGTH = SODIUM_CRYPTO_SIGN_KEYPAIRBYTES;
    private const PUBLIC_KEY_LENGTH = SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES;
    private const PRIVATE_KEY_LENGTH = SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

    private const CONFIG_PATH = 'crypto.signing.keys.eddsa';

    private static string $privateKey;
    private static string $publicKey;

    public function __construct(
        private readonly Repository $config,
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function init(Repository $config, LoggerInterface $logger): void
    {
        $path = $config->get(self::CONFIG_PATH);

        if ($path === null) {
            throw new MissingAppKeyException('File for EdDSA signer is not set');
        }

        [self::$publicKey, self::$privateKey] = self::parseKeys($path, $logger);
    }

    protected static function parseKeys(string $keyPath, LoggerInterface $logger): array
    {
        $file = new SplFileObject($keyPath, 'rb');
        if ($file->flock(LOCK_SH) === false) {
            throw new RuntimeException('Error while locking file (shared/reading)');
        }

        try {
            $keys = $file->fread(self::KEY_LENGTH * 2 + 1);

            if ($keys === false) {
                throw new RuntimeException('Error while reading key');
            }
        } finally {
            if ($file->flock(LOCK_UN) === false) {
                $logger->warning('Error while unlocking file');
            }
        }

        [$publicKey, $privateKey] = explode(PHP_EOL, $keys, 2);

        return [hex2bin($publicKey), hex2bin($privateKey)];
    }

    public function getKey(): string|array
    {
        return [self::$publicKey, self::$privateKey];
    }

    public function generate(bool $write): ?string
    {
        $keyPair = sodium_crypto_sign_keypair();
        $privateKey = bin2hex(sodium_crypto_sign_secretkey($keyPair));
        $publicKey = bin2hex(sodium_crypto_sign_publickey($keyPair));

        $key = implode(PHP_EOL, [$publicKey, $privateKey]);

        if (!$write) {
            return $key;
        }


        $path = $this->config->get(self::CONFIG_PATH);

        if ($path === null) {
            throw new RuntimeException('File for EdDSA signer is not set');
        }

        $file = new SplFileObject($path, 'wb');

        if ($file->flock(LOCK_EX) === false) {
            throw new RuntimeException('Error while locking file (exclusive/writing)');
        }

        try {
            if ($file->fwrite($key) === false) {
                throw new RuntimeException('Error while writing public key to file');
            }
        } finally {
            if ($file->flock(LOCK_UN) === false) {
                $this->logger->warning('Error while unlocking file');
            }

            sodium_memzero($privateKey);
            sodium_memzero($publicKey);
            sodium_memzero($keyPair);
            sodium_memzero($key);
        }

        return null;
    }
}