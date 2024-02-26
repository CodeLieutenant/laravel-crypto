<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Traits;

use BrosSquad\LaravelCrypto\Support\Base64;
use BrosSquad\LaravelCrypto\Traits\ConstantTimeCompare;

trait Signing
{
    use ConstantTimeCompare;

    public function verify(string $message, string $hmac, bool $decodeSignature = true): bool
    {
        return $this->equals(
            $this->signRaw($message),
            !$decodeSignature ? $hmac : Base64::constantUrlDecodeNoPadding($hmac)
        );
    }

    public function sign(string $data): string
    {
        return Base64::constantUrlEncodeNoPadding($this->signRaw($data));
    }

}
