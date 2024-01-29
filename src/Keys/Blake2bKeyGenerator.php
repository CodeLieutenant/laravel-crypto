<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Support\Random;
use Illuminate\Contracts\Config\Repository;

class Blake2bKeyGenerator implements Generator
{
    use EnvKeySaver;

    public const KEY_SIZE = 32;
    public const ENV = 'CRYPTO_BLAKE2B_HASHING_KEY';


    public function __construct(
        private readonly Repository $config,
    ) {
    }

    public function generate(): void
    {
        $old = $this->config->get('crypto.hashing.config.blake2b.key');
        $new = $this->formatKey(Random::bytes(self::KEY_SIZE));

        $this->config->set('crypto.hashing.config.blake2b.key', $new);

        $this->writeNewEnvironmentFileWith([
            self::ENV => [
                'old' => $old,
                'new' => $new,
            ],
        ]);
    }
}