<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

use CodeLieutenant\LaravelCrypto\Support\Random;
use CodeLieutenant\LaravelCrypto\Traits\EnvKeySaver;
use Illuminate\Contracts\Config\Repository;

class Blake2bHashingKeyGenerator implements Generator
{
    use EnvKeySaver;

    public const KEY_SIZE = 32;

    public const ENV = 'CRYPTO_BLAKE2B_HASHING_KEY';
    public const CONFIG_KEY_PATH = 'crypto.hashing.config.blake2b.key';

    public function __construct(protected readonly Repository $config)
    {
    }

    public function generate(?string $write): ?string
    {
        $old = $this->config->get(static::CONFIG_KEY_PATH);
        $new = $this->formatKey(Random::bytes(static::KEY_SIZE));

        $this->config->set(static::CONFIG_KEY_PATH, $new);

        if ($write === null) {
            return $new;
        }

        $this->writeNewEnvironmentFileWith($write, [
            static::ENV => [
                'old' => $old ?? '',
                'new' => $new,
            ],
        ]);

        return null;
    }
}