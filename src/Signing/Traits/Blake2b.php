<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Signing\Traits;

trait Blake2b
{
    protected ?\CodeLieutenant\LaravelCrypto\Signing\Hmac\Blake2b $blake2b = null;

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

    public function createBlake2bDriver(): \CodeLieutenant\LaravelCrypto\Signing\Hmac\Blake2b
    {
        if ($this->blake2b === null) {
            $this->blake2b = $this->container->get(Blake2b::class);
        }

        return $this->blake2b;
    }

}