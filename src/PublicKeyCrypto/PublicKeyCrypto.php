<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\PublicKeyCrypto;

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
        sodium_memzero($this->privateKey);
        sodium_memzero($this->publicKey);
    }
}
