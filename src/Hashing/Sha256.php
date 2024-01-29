<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

class Sha256 extends Hash
{
    public const ALGORITHM = 'sha512/256';
    public const HASH_SIZE = 64;

    public function hash(string $data): string
    {
        return hash(static::ALGORITHM, $data);
    }

    public function hashRaw(string $data): string
    {
        return hash(static::ALGORITHM, $data, true);
    }
}
