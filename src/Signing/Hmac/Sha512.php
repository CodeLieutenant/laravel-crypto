<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Signing\Hmac;

use CodeLieutenant\LaravelCrypto\Contracts\Signing as SigningContract;
use CodeLieutenant\LaravelCrypto\Keys\Loader;
use CodeLieutenant\LaravelCrypto\Signing\Traits\Signing;

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
