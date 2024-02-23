<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Support\Base64;

class HmacBlake2b extends Hmac
{
    public const HASH_SIZE = 64;
    public const ALGORITHM = 'blake2b';

    public function sign(string $data): string
    {
        return Base64::constantUrlEncodeNoPadding($this->signRaw($data));
    }

    public function signRaw(string $data): string
    {
        return sodium_crypto_generichash($data, $this->loader->getKey(), self::HASH_SIZE);
    }
}
