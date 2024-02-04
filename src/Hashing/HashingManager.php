<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

use Illuminate\Support\Manager;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

class HashingManager extends Manager implements Hashing
{
    use Traits\Blake2b;
    use Traits\Sha256;
    use Traits\Sha512;

    public function equals(string $hash1, string $hash2): bool
    {
        return sodium_memcmp($hash1, $hash2) === 0;
    }

    public function hash(string $data): string
    {
        return $this->driver($this->getDefaultDriver())->hash($data);
    }

    public function hashRaw(string $data): string
    {
        return $this->driver($this->getDefaultDriver())->hashRaw($data);
    }

    public function verify(string $hash, string $data): bool
    {
        return $this->driver($this->getDefaultDriver())->verify($hash, $data);
    }

    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->createBlake2bDriver()->verifyRaw($hash, $data);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('crypto.hashing.driver', 'blake2b');
    }
}
