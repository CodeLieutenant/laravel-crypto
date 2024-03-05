<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Loaders;

use CodeLieutenant\LaravelCrypto\Contracts\KeyLoader;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\MissingAppKeyException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SplFileObject;

class EdDSASignerKeyLoader implements KeyLoader
{
    public const KEY_LENGTH = SODIUM_CRYPTO_SIGN_KEYPAIRBYTES;
    public const PUBLIC_KEY_LENGTH = SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES;
    public const PRIVATE_KEY_LENGTH = SODIUM_CRYPTO_SIGN_SECRETKEYBYTES;

    private const CONFIG_KEY_PATH = 'crypto.signing.keys.eddsa';

    private static string $privateKey;
    private static string $publicKey;


    public static function make(Repository $config, LoggerInterface $logger): static
    {
        if (!isset(static::$publicKey, static::$privateKey)) {
            $path = $config->get(static::CONFIG_KEY_PATH);

            if ($path === null) {
                throw new MissingAppKeyException('File for EdDSA signer is not set');
            }

            [static::$publicKey, static::$privateKey] = static::parseKeys($path, $logger);
        }

        return new static();
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


}