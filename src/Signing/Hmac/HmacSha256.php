<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use SodiumException;
use BrosSquad\LaravelCrypto\Support\Base64;

class HmacSha256 extends Hmac
{
    public const HASH_SIZE = 32;
    public const ALGORITHM = 'sha512/256';

    public function sign(string $data): string
    {
        return Base64::constantUrlEncode(sodium_crypto_auth($data, $this->loader->getKey()));
    }

    public function signRaw(string $data): string
    {
        return sodium_crypto_auth($data, $this->loader->getKey());
    }

    public function verify(string $message, string $hmac): bool
    {
        return sodium_crypto_auth_verify($hmac, $message, $this->loader->getKey());
    }
}
