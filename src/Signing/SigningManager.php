<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing;

use BrosSquad\LaravelCrypto\LaravelKeyParser;
use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSA;
use Illuminate\Support\Manager;
use BrosSquad\LaravelCrypto\Contracts\Signing;
use BrosSquad\LaravelCrypto\Signing\Hmac\{Blake2b, Hmac256, Hmac512};

class SigningManager extends Manager implements Signing
{
    use LaravelKeyParser;

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

    public function hmac256Sign(string $data): string
    {
        return $this->driver('hmac256')->sign($data);
    }

    public function hmac256SignRaw(string $data): string
    {
        return $this->driver('hmac256')->signRaw($data);
    }

    public function hmac256Verify(string $message, string $hmac): bool
    {
        return $this->driver('hmac256')->verify($message, $hmac);
    }

    public function hmac512Sign(string $data): string
    {
        return $this->driver('hmac512')->sign($data);
    }

    public function hmac512SignRaw(string $data): string
    {
        return $this->driver('hmac512')->signRaw($data);
    }


    public function hmac512Verify(string $message, string $hmac): bool
    {
        return $this->driver('hmac512')->verify($message, $hmac);
    }

    public function eddsaSign(string $data): string
    {
        return $this->driver('eddsa')->sign($data);
    }

    public function eddsaSignRaw(string $data): string
    {
        return $this->driver('eddsa')->signRaw($data);
    }


    public function eddsaVerify(string $message, string $hmac): bool
    {
        return $this->driver('eddsa')->verify($message, $hmac);
    }


    public function getDefaultDriver()
    {
        return $this->config->get('crypto.signing.driver', 'blake2b');
    }

    public function createBlake2bDriver(): Blake2b
    {
        return new Blake2b($this->getKey());
    }

    public function createHmac512Driver(): Hmac512
    {
        return new Hmac512($this->getKey());
    }

    public function createHmac256Driver(): Hmac256
    {
        return new Hmac256($this->getKey());
    }

    public function createEddsaManager(): EdDSA
    {
        return new EdDSA(
            ...EdDSA::getPublicAndPrivateEdDSAKey($this->config->get('crypto.public_key_crypto.eddsa'))
        );
    }
}
