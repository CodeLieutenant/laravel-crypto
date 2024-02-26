<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Contracts\Signing as SigningContract;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Signing\Traits\Signing;

final class Sha512 implements SigningContract
{
    use Signing;

    public function __construct(
        private readonly Loader $loader,
    ) {
    }

    public function signRaw(string $data): string
    {
        return hash_hmac('sha512', $data, $this->loader->getKey(), true);
    }
}
