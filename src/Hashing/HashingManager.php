<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use Illuminate\Support\Manager;
use SodiumException;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

class HashingManager extends Manager implements Hashing
{
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

    public function hash(string $data): ?string
    {
        return $this->driver()->hash($data);
    }

    public function hashRaw(string $data): ?string
    {
        return $this->driver()->hashRaw($data);
    }

    public function verify(string $hash, string $data): bool
    {
        return $this->driver()->verify($hash, $data);
    }

    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->driver()->verifyRaw($hash, $data);
    }

    public function sha256(string $data): string
    {
        return $this->driver('sha256')->hash($data);
    }

    public function sha256Raw(string $data): string
    {
        return $this->driver('sha256')->hashRaw($data);
    }

    public function sha256Verify(string $hash, string $data): bool
    {
        return $this->driver('sha256')->verify($hash, $data);
    }

    public function sha256VerifyRaw(string $hash, string $data): bool
    {
        return $this->driver('sha256')->verifyRaw($hash, $data);
    }


    public function sha512(string $data): string
    {
        return $this->driver('sha512')->hash($data);
    }

    public function sha512Raw(string $data): string
    {
        return $this->driver('sha512')->hashRaw($data);
    }


    public function sha512Verify(string $hash, string $data): bool
    {
        return $this->driver('sha512')->verify($hash, $data);
    }

    public function sha512VerifyRaw(string $hash, string $data): bool
    {
        return $this->driver('sha512')->verifyRaw($hash, $data);
    }


    public function getDefaultDriver()
    {
        return $this->config->get('crypto.hashing.driver', 'blake2b');
    }

    public function createBlake2bDriver(): Blake2b
    {
        return new Blake2b();
    }

    public function createSha256Driver(): Sha256
    {
        return new Sha256();
    }

    public function createSha512Driver(): Sha512
    {
        return new Sha512();
    }
}
