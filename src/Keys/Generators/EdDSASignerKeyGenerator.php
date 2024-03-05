<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

use CodeLieutenant\LaravelCrypto\Contracts\KeyGenerator;
use Illuminate\Contracts\Config\Repository;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SplFileObject;

class EdDSASignerKeyGenerator implements KeyGenerator
{
    private const CONFIG_KEY_PATH = 'crypto.signing.keys.eddsa';

    public function __construct(
        private readonly Repository $config,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function generate(?string $write): ?string
    {
        $keyPair = sodium_crypto_sign_keypair();
        $privateKey = bin2hex(sodium_crypto_sign_secretkey($keyPair));
        $publicKey = bin2hex(sodium_crypto_sign_publickey($keyPair));

        $key = implode(PHP_EOL, [$publicKey, $privateKey]);

        if ($write === null) {
            return $key;
        }

        $path = $this->config->get(self::CONFIG_KEY_PATH);

        if ($path === null) {
            throw new RuntimeException('File for EdDSA signer is not set');
        }

        if (!@file_exists($concurrentDirectory = dirname($path)) && !@mkdir(
                $concurrentDirectory,
                0740,
                true
            ) && !is_dir(
                $concurrentDirectory
            )) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
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