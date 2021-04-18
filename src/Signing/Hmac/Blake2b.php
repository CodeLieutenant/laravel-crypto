<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use BrosSquad\LaravelCrypto\Support\Base64;
use SodiumException;

class Blake2b extends Hmac
{
    public function sign(string $data): ?string
    {
        return Base64::constantUrlEncodeNoPadding($this->signRaw($data));
    }

    public function signRaw(string $data): ?string
    {
        try {
            return sodium_crypto_generichash($data, $this->key, 64);
        } catch (SodiumException $e) {
            return null;
        }
    }
}
