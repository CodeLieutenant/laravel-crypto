<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Contracts\Signing as HmacContract;

abstract class Hmac implements HmacContract
{
    public const HASH_SIZE = 0;
    public const ALGORITHM = '';

    public function __construct(protected readonly Loader $loader)
    {
    }

    public function verify(string $message, string $hmac): bool
    {
        $generated = $this->sign($message);

        return sodium_memcmp($generated, $hmac) === 0;
    }
}
