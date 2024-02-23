<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Traits;

trait Blake2b
{
    protected ?\BrosSquad\LaravelCrypto\Signing\Hmac\HmacBlake2b $blake2b = null;

    public function blake2bSign(string $data): string
    {
        return $this->createBlake2bDriver()->sign($data);
    }

    public function blake2bSignRaw(string $data): string
    {
        return $this->createBlake2bDriver()->signRaw($data);
    }

    public function blake2bVerify(string $message, string $hmac): bool
    {
        return $this->createBlake2bDriver()->verify($message, $hmac);
    }

    public function createBlake2bDriver(): \BrosSquad\LaravelCrypto\Signing\Hmac\HmacBlake2b
    {
        if ($this->blake2b === null) {
            $this->blake2b = $this->container->get(Blake2b::class);
        }

        return $this->blake2b;
    }

}