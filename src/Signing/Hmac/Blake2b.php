<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Contracts\Signing as SigningContract;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Signing\Traits\Signing;

final class Blake2b implements SigningContract
{
    use Signing;

    public function __construct(
        private readonly Loader $loader,
        private readonly int $outputSize
    ) {
    }

    public function signRaw(string $data): string
    {
        return sodium_crypto_generichash($data, $this->loader->getKey(), $this->outputSize);
    }
}
