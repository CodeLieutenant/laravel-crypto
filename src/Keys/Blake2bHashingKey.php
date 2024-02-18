<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Support\Random;
use Illuminate\Contracts\Config\Repository;

class Blake2bHashingKey extends AppKey
{
    use EnvKeySaver;
    use LaravelKeyParser;

    public const KEY_SIZE = 32;
    public const ENV = 'CRYPTO_BLAKE2B_HASHING_KEY';
    protected const CONFIG_KEY_PATH = 'crypto.hashing.config.blake2b.key';

    public function generate(?string $write): ?string
    {
        $old = $this->config->get(static::CONFIG_CIPHER_PATH);
        $new = $this->formatKey(Random::bytes(static::KEY_SIZE));

        $this->config->set(static::CONFIG_CIPHER_PATH, $new);

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

    public function getKey(): string|array
    {
        return static::$key;
    }
}