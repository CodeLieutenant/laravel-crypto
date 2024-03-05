<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

use CodeLieutenant\LaravelCrypto\Contracts\KeyGenerator;
use CodeLieutenant\LaravelCrypto\Encryption\AesGcm256Encrypter;
use CodeLieutenant\LaravelCrypto\Encryption\XChaCha20Poly1305Encrypter;
use CodeLieutenant\LaravelCrypto\Enums\Encryption;
use CodeLieutenant\LaravelCrypto\Traits\EnvKeySaver;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\Encrypter;

class AppKeyGenerator implements KeyGenerator
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
                Encryption::SodiumAES256GCM => sodium_crypto_aead_aes256gcm_keygen(),
                Encryption::SodiumXChaCha20Poly1305 => sodium_crypto_aead_xchacha20poly1305_ietf_keygen(),
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