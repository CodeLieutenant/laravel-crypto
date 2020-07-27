<?php


namespace BrosSquad\LaravelHashing\Common;


use SodiumException;
use BrosSquad\LaravelHashing\Contracts\Hashing;

abstract class Hash implements Hashing
{
    public function equals(string $hash1, string $hash2): bool
    {
        if(function_exists('sodium_memcmp')) {
            try {
                return sodium_memcmp($hash1, $hash2) === 0;
            } catch (SodiumException $e) {
                return false;
            }
        }

        return hash_equals($hash1, $hash2);
    }
}
