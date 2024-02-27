<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;


class HmacKey extends Blake2bHashingKey
{
    use LaravelKeyParser;
    use EnvKeySaver;

    public const ENV = 'CRYPTO_HMAC_KEY';
    protected const CONFIG_KEY_PATH = 'crypto.signing.keys.hmac';
}