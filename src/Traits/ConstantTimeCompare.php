<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Traits;

trait ConstantTimeCompare
{
    public function equals(string $hash1, string $hash2): bool
    {
        $len1 = strlen($hash1);
        $len2 = strlen($hash2);

         if ($len1 > $len2) {
            $hash2 = sodium_pad($hash2, $len1);
        } elseif ($len2 > $len1) {
            $hash1 = sodium_pad($hash1, $len2);
        }

        return sodium_memcmp($hash1, $hash2) === 0;
    }
}