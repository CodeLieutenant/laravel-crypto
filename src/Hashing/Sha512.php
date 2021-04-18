<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Hashing;

/**
 * Class Sha512
 *
 * @package BrosSquad\LaravelCrypto\Common
 */
class Sha512 extends Hash
{
    protected string $algo = 'sha3-512';

    /**
     * @param  string  $data
     *
     * @return string
     */
    public function hash(string $data): string
    {
        return hash($this->algo, $data);
    }

    public function hashRaw(string $data): ?string
    {
        return hash($this->algo, $data, true);
    }
}
