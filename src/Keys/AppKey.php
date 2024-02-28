<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encryptor;
use CodeLieutenant\LaravelCrypto\Encryption\Encryption;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encryptor;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\Encrypter;

class AppKey implements Loader
{
    use LaravelKeyParser;

    protected static string $key;

    public const CONFIG_KEY_PATH = 'app.key';

    public static function make(Repository $config): static
    {
        if (!isset(static::$key)) {
            static::$key = self::parseKey($config->get(static::CONFIG_KEY_PATH));
        }

        return new static();
    }

    public function getKey(): string|array
    {
        return self::$key;
    }
}