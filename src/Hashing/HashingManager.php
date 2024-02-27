<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Hashing;

use CodeLieutenant\LaravelCrypto\Traits\ConstantTimeCompare;
use Illuminate\Support\Manager;
use CodeLieutenant\LaravelCrypto\Contracts\Hashing;

class HashingManager extends Manager implements Hashing
{
    use Traits\Blake2b;
    use Traits\Sha256;
    use Traits\Sha512;
    use ConstantTimeCompare;

    public function hash(string $data): string
    {
        return $this->driver()->hash($data);
    }

    public function hashRaw(string $data): string
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

    public function getDefaultDriver()
    {
        return $this->config->get('crypto.hashing.driver', 'blake2b');
    }
}
