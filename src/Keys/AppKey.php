<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encryptor;
use CodeLieutenant\LaravelCrypto\Encryption\Encryption;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encryptor;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\Encrypter;

class AppKey implements Loader, Generator
{
    use EnvKeySaver;
    use LaravelKeyParser;

    protected static string $key;

    public const ENV = 'APP_KEY';
    protected const CONFIG_KEY_PATH = 'app.key';
    protected const CONFIG_CIPHER_PATH = 'app.cipher';

    public function __construct(protected readonly Repository $config)
    {
    }

    public static function init(Repository $config): static
    {
        if (!isset(static::$key)) {
            static::$key = self::parseKey($config->get(static::CONFIG_KEY_PATH));
        }

        return new static($config);
    }


    public function getKey(): string|array
    {
        return self::$key;
    }

    public function generate(?string $write): ?string
    {
        $old = $this->config->get(static::CONFIG_KEY_PATH);
        $cipher = $this->config->get(static::CONFIG_CIPHER_PATH);

        $new = $this->formatKey(
            match (Encryption::tryFrom($cipher)) {
                Encryption::SodiumAES256GCM => AesGcm256Encryptor::generateKey($cipher),
                Encryption::SodiumXChaCha20Poly1305 => XChaCha20Poly1305Encryptor::generateKey($cipher),
                default => Encrypter::generateKey($cipher),
            }
        );

        if ($write === null) {
            return $new;
        }

        $this->config->set(static::CONFIG_KEY_PATH, $new);

        $this->writeNewEnvironmentFileWith($write, [
            static::ENV => [
                'old' => $old,
                'new' => $new,
            ],
        ]);

        return null;
    }
}