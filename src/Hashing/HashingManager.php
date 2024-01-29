<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use Illuminate\Support\Manager;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

class HashingManager extends Manager implements Hashing
{
    private ?Blake2b $blake2b = null;
    private ?Sha256 $sha256 = null;
    private ?Sha512 $sha512 = null;

    public function equals(string $hash1, string $hash2): bool
    {
        return sodium_memcmp($hash1, $hash2) === 0;
    }

    public function hash(string $data): string
    {
        return $this->createBlake2bDriver()->hash($data);
    }

    public function hashRaw(string $data): string
    {
        return $this->createBlake2bDriver()->hashRaw($data);
    }

    public function verify(string $hash, string $data): bool
    {
        return $this->createBlake2bDriver()->verify($hash, $data);
    }

    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->createBlake2bDriver()->verifyRaw($hash, $data);
    }

    public function sha256(string $data): string
    {
        return $this->createSha256Driver()->hash($data);
    }

    public function sha256Raw(string $data): string
    {
        return $this->createSha256Driver()->hashRaw($data);
    }

    public function sha256Verify(string $hash, string $data): bool
    {
        return $this->createSha256Driver()->verify($hash, $data);
    }

    public function sha256VerifyRaw(string $hash, string $data): bool
    {
        return $this->createSha256Driver()->verifyRaw($hash, $data);
    }


    public function sha512(string $data): string
    {
        return $this->createSha512Driver()->hash($data);
    }

    public function sha512Raw(string $data): string
    {
        return $this->createSha512Driver()->hashRaw($data);
    }


    public function sha512Verify(string $hash, string $data): bool
    {
        return $this->createSha512Driver()->verify($hash, $data);
    }

    public function sha512VerifyRaw(string $hash, string $data): bool
    {
        return $this->createSha512Driver()->verifyRaw($hash, $data);
    }


    public function getDefaultDriver()
    {
        return $this->config->get('crypto.hashing.driver', 'blake2b');
    }

    public function createBlake2bDriver(): Blake2b
    {
        if ($this->blake2b === null) {
            $this->blake2b = new Blake2b();
        }

        return $this->blake2b;
    }

    public function createSha256Driver(): Sha256
    {
        if ($this->sha256 === null) {
            $this->sha256 = new Sha256();
        }

        return $this->sha256;
    }

    public function createSha512Driver(): Sha512
    {
        if ($this->sha512 === null) {
            $this->sha512 = new Sha512();
        }

        return $this->sha512;
    }
}
