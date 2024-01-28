<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;

class Sha512 extends Hash
{
    protected string $algo = 'sha512';

    public function hash(string $data): string
    {
        return hash($this->algo, $data);
    }

    public function hashRaw(string $data): ?string
    {
        return hash($this->algo, $data, true);
    }
}
