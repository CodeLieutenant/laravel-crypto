<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Support\Random;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Encryption\MissingAppKeyException;

use function _PHPStan_11268e5ee\React\Promise\all;

class Blake2bHashingKey extends AppKey
{
    use EnvKeySaver;
    use LaravelKeyParser;

    public const KEY_SIZE = 32;
    public const ENV = 'CRYPTO_BLAKE2B_HASHING_KEY';
    protected const CONFIG_PATH = 'crypto.hashing.config.blake2b.key';

    public static function init(Repository $config): void
    {
        self::$key = self::parseKey($config->get(self::CONFIG_PATH), allowEmpty: true);
    }

    public function generate(bool $write): ?string
    {
        $old = $this->config->get(static::CONFIG_PATH);
        $new = $this->formatKey(Random::bytes(static::KEY_SIZE));

        $this->config->set(static::CONFIG_PATH, $new);

        if (!$write) {
            return $new;
        }

        $this->writeNewEnvironmentFileWith([
            self::ENV => [
                'old' => $old,
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