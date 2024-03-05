<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

class HmacKeyGenerator extends Blake2BHashingKeyGenerator
{
    public const ENV = 'CRYPTO_HMAC_KEY';
    public const CONFIG_KEY_PATH = 'crypto.signing.keys.hmac';
}