<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\PublicKeyCrypto;

use SodiumException;

abstract class PublicKeyCrypto
{
    protected string $privateKey;
    protected string $publicKey;

    public function __construct(string $privateKey, string $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    public function __destruct()
    {
        try {
            sodium_memzero($this->privateKey);
            sodium_memzero($this->publicKey);
        } catch (SodiumException $e) {
        }
    }
}
