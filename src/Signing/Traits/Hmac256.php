<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Traits;

use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha256;

trait Hmac256
{
    protected ?HmacSha256 $hmac256 = null;

    public function hmac256Sign(string $data): string
    {
        return $this->createHmac256Driver()->sign($data);
    }

    public function hmac256SignRaw(string $data): string
    {
        return $this->createHmac256Driver()->signRaw($data);
    }

    public function hmac256Verify(string $message, string $hmac): bool
    {
        return $this->createHmac256Driver()->verify($message, $hmac);
    }

    public function createHmac256Driver(): HmacSha256
    {
        if ($this->hmac256 === null) {
            $this->hmac256 = $this->container->get(HmacSha256::class);
        }

        return $this->hmac256;
    }

}