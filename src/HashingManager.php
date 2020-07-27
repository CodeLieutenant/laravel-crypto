<?php


namespace BrosSquad\LaravelHashing;


use SodiumException;
use BrosSquad\LaravelHashing\Common\Sha256;
use BrosSquad\LaravelHashing\Common\Sha512;
use BrosSquad\LaravelHashing\Common\Blake2b;
use BrosSquad\LaravelHashing\Contracts\Hashing;

class HashingManager implements Hashing
{

    public function hash(string $data): ?string
    {
        return (new Blake2b())->hash($data);
    }

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

    public function sha256(string $data): string {
        return (new Sha256())->hash($data);
    }

    public function sha512(string $data): string {
        return (new Sha512())->hash($data);
    }

}
