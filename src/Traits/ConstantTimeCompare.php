<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Traits;

trait ConstantTimeCompare
{
    public function equals(string $hash1, string $hash2): bool
    {
        return sodium_memcmp($hash1, $hash2) === 0;
    }
}