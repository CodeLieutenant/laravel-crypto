<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Traits;

use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha512;

trait Hmac512
{
    protected ?HmacSha512 $hmac512 = null;

    public function hmac512Sign(string $data): string
    {
        return $this->createHmac512Driver()->sign($data);
    }

    public function hmac512SignRaw(string $data): string
    {
        return $this->createHmac512Driver()->signRaw($data);
    }


    public function hmac512Verify(string $message, string $hmac): bool
    {
        return $this->createHmac512Driver()->verify($message, $hmac);
    }

    public function createHmac512Driver(): HmacSha512
    {
        if ($this->hmac512 === null) {
            $this->hmac512 = $this->container->get(HmacSha512::class);
        }

        return $this->hmac512;
    }
}