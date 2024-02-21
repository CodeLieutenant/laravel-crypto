<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing;

use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use Illuminate\Support\Manager;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Signing\Hmac\Blake2b;
use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha256;
use BrosSquad\LaravelCrypto\Signing\Hmac\HmacSha512;

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

    public function verify(string $message, string $hmac): bool
    {
        return $this->driver()->verify($message, $hmac);
    }

    public function getDefaultDriver()
    {
        return $this->config->get('crypto.signing.driver', 'blake2b');
    }

}
