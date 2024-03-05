<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encrypter;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encrypter;
use CodeLieutenant\LaravelCrypto\Enums\Encryption;
use CodeLieutenant\LaravelCrypto\Keys\EnvKeySaver;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\Encrypter;

class AppKeyGenerator implements Generator
{
    use EnvKeySaver;

    protected const CONFIG_CIPHER_PATH = 'app.cipher';
    protected const CONFIG_KEY_PATH = 'app.key';

    public const ENV = 'APP_KEY';

    public function __construct(protected readonly Repository $config)
    {
    }

    public function generate(?string $write): ?string
    {
        $old = $this->config->get(static::CONFIG_KEY_PATH);
        $cipher = $this->config->get(static::CONFIG_CIPHER_PATH);

        $new = $this->formatKey(
            match (Encryption::tryFrom($cipher)) {
                Encryption::SodiumAES256GCM => AesGcm256Encrypter::generateKey($cipher),
                Encryption::SodiumXChaCha20Poly1305 => XChaCha20Poly1305Encrypter::generateKey($cipher),
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