<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;


use SodiumException;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

abstract class Hash implements Hashing
{
    public const ALGORITHM = '';
    public const HASH_SIZE = 0;

    public function equals(string $hash1, string $hash2): bool
    {
        return sodium_memcmp($hash1, $hash2) === 0;
    }

    public function verify(string $hash, string $data): bool
    {
        return $this->equals($hash, $this->hash($data));
    }

    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->equals($hash, $this->hashRaw($data));
    }
}
