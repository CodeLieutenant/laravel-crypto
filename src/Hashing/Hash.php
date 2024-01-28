<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Hashing;


use SodiumException;
use BrosSquad\LaravelCrypto\Contracts\Hashing;

abstract class Hash implements Hashing
{
    protected string $algo;

    /**
     * @param string $hash1
     * @param string $hash2
     *
     * @return bool
     * @throws SodiumException
     */
    public function equals(string $hash1, string $hash2): bool
    {
        return sodium_memcmp($hash1, $hash2) === 0;
    }

    /**
     *
     * @param string $hash
     * @param string $data
     * @return bool
     * @throws SodiumException
     */
    public function verify(string $hash, string $data): bool
    {
        return $this->equals($hash, $this->hash($data));
    }

    /**
     *
     * @param string $hash
     * @param string $data
     * @return bool
     * @throws SodiumException
     */
    public function verifyRaw(string $hash, string $data): bool
    {
        return $this->equals($hash, $this->hashRaw($data));
    }

    public function getAlgorithm(): string
    {
        return $this->algo;
    }
}
