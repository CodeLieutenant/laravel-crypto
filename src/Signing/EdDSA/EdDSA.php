<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\EdDSA;

use BrosSquad\LaravelCrypto\Contracts\PublicKeySigning;
use BrosSquad\LaravelCrypto\Keys\Loader;
use BrosSquad\LaravelCrypto\Support\Base64;
use Psr\Log\LoggerInterface;
use SodiumException;

class EdDSA implements PublicKeySigning
{
    public function __construct(
        protected readonly Loader $loader,
        protected readonly LoggerInterface $logger,
    ) {
    }

    public function sign(string $data): string
    {
        return Base64::urlEncodeNoPadding($this->signRaw($data));
    }

    public function signRaw(string $data): string
    {
        [, $private] = $this->loader->getKey();
        return sodium_crypto_sign_detached($data, $private);
    }

    public function verify(string $message, string $hmac): bool
    {
        [$public] = $this->loader->getKey();

        try {
            return sodium_crypto_sign_verify_detached(Base64::urlDecode($hmac), $message, $public);
        } catch (SodiumException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }
}
