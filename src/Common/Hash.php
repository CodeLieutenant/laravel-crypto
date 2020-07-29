<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Common;


use SodiumException;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

/**
 * Class Hash
 *
 * @package BrosSquad\LaravelCrypto\Common
 */
abstract class Hash implements Hashing
{

    /**
     * @param  string  $hash1
     * @param  string  $hash2
     *
     * @return bool
     */
    public function equals(string $hash1, string $hash2): bool
    {
        if (function_exists('sodium_memcmp')) {
            try {
                return sodium_memcmp($hash1, $hash2) === 0;
            } catch (SodiumException $e) {
                return false;
            }
        }

        return hash_equals($hash1, $hash2);
    }
}
