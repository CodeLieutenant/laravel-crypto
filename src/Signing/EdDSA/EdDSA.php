<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Signing\EdDSA;

use CodeLieutenant\LaravelCrypto\Contracts\PublicKeySigning;
use CodeLieutenant\LaravelCrypto\Keys\KeyLoader;
use CodeLieutenant\LaravelCrypto\Signing\Traits\Signing;
use CodeLieutenant\LaravelCrypto\Support\Base64;
final class EdDSA implements PublicKeySigning
{
    use Signing;

    public function __construct(
        private readonly KeyLoader $loader,
    ) {
    }

    public function signRaw(string $data): string
    {
        [, $private] = $this->loader->getKey();
        return sodium_crypto_sign_detached($data, $private);
    }

    public function verify(string $message, string $hmac, bool $decodeSignature = true): bool
    {
        [$public] = $this->loader->getKey();
        return sodium_crypto_sign_verify_detached(
            !$decodeSignature ? $hmac : Base64::urlDecode($hmac),
            $message,
            $public
        );
    }
}
