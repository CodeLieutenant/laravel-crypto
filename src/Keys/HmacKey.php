<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use Illuminate\Contracts\Config\Repository;

class HmacKey extends Blake2bHashingKey
{
    use LaravelKeyParser;
    use EnvKeySaver;

    public const ENV = 'CRYPTO_HMAC_KEY';
    protected const CONFIG_PATH = 'crypto.signing.keys.hmac';

    public static function init(Repository $config): void
    {
        static::$key = static::parseKey($config->get(static::CONFIG_PATH));
    }
}