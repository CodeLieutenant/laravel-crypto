<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Contracts\Signing as SigningContract;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Signing\Traits\Signing;
use BrosSquad\LaravelCrypto\Support\Base64;

final class Sha256 implements SigningContract
{
    use Signing;

    public function __construct(
        private readonly Loader $loader,
    ) {
    }

    public function signRaw(string $data): string
    {
        return sodium_crypto_auth($data, $this->loader->getKey());
    }

    public function verify(string $message, string $hmac, bool $decodeSignature = true): bool
    {
        return sodium_crypto_auth_verify(
            !$decodeSignature ? $hmac : Base64::constantUrlDecodeNoPadding($hmac),
            $message,
            $this->loader->getKey()
        );
    }
}
