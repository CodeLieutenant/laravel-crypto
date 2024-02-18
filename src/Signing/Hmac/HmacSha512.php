<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

class HmacSha512 extends Hmac
{
    public const HASH_SIZE = 64;
    public const ALGORITHM = 'sha512';

    public function sign(string $data): string
    {
        return hash_hmac(self::ALGORITHM, $data, $this->loader->getKey());
    }

    public function signRaw(string $data): string
    {
        return hash_hmac(self::ALGORITHM, $data, $this->loader->getKey(), true);
    }
}
