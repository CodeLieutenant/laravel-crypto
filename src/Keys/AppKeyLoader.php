<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Encryption\AesGcm256Encryptor;
use BrosSquad\LaravelCrypto\Encryption\Encryption;
use BrosSquad\LaravelCrypto\Encryption\XChaCha20Poly5Encryptor;
use BrosSquad\LaravelCrypto\Support\Base64;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\Encrypter;
use Illuminate\Encryption\MissingAppKeyException;
use RuntimeException;

class AppKeyLoader implements Loader, Generator
{
    use EnvKeySaver;

    private readonly string $key;

    public const ENV = 'APP_ENV';


    public function __construct(private readonly Repository $config)
    {
        $this->key = $this->parseKey($this->config->get('app.key'));
    }

    protected function parseKey(string $key): string
    {
        if (empty($key)) {
            throw new MissingAppKeyException();
        }

        if (str_starts_with($key, $prefix = 'base64:')) {
            return Base64::decode(substr($key, strlen($prefix)));
        }

        $key = hex2bin($key);

        if ($key === false) {
            throw new RuntimeException('Application encryption key is not a valid hex string.');
        }

        return $key;
    }

    public function getKey(): string|array
    {
        return $this->key;
    }

    public function generate(): void
    {
        $old = $this->config->get('app.key');
        $cipher = $this->config->get('app.cipher');

        $new = $this->formatKey(
            match (Encryption::tryFrom($cipher)) {
                Encryption::SodiumAES256GCM => AesGcm256Encryptor::generateKey($cipher),
                Encryption::SodiumXChaCha20Poly1305 => XChaCha20Poly5Encryptor::generateKey($cipher),
                default => Encrypter::generateKey($cipher),
            }
        );

        $this->config->set('app.key', $new);

        $this->writeNewEnvironmentFileWith([
            'APP_KEY' => [
                'old' => $old,
                'new' => $new,
            ],
        ]);
    }
}