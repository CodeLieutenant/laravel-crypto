<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Signing;

use Illuminate\Support\Manager;
use CodeLieutenant\LaravelCrypto\Contracts\Signing;

class SigningManager extends Manager implements Signing
{
    use Traits\Blake2b;
    use Traits\Hmac256;
    use Traits\Hmac512;
    use Traits\EdDSA;

    public function sign(string $data): string
    {
        return $this->driver()->sign($data);
    }

    public function signRaw(string $data): string
    {
        return $this->driver()->signRaw($data);
    }

    public function verify(string $message, string $hmac, bool $decodeSignature = true): bool
    {
        return $this->driver()->verify($message, $hmac, $decodeSignature);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('crypto.signing.driver', 'blake2b');
    }

}
