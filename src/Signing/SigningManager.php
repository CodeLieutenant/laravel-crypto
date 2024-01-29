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
    private ?Blake2b $blake2b = null;
    private ?HmacSha256 $hmac256 = null;
    private ?HmacSha512 $hmac512 = null;
    private ?EdDSA $eddsa = null;


    public function sign(string $data): string
    {
        return $this->createBlake2bDriver()->sign($data);
    }

    public function signRaw(string $data): string
    {
        return $this->createBlake2bDriver()->signRaw($data);
    }

    public function verify(string $message, string $hmac): bool
    {
        return $this->createBlake2bDriver()->verify($message, $hmac);
    }

    public function hmac256Sign(string $data): string
    {
        return $this->createHmac256Driver()->sign($data);
    }

    public function hmac256SignRaw(string $data): string
    {
        return $this->createHmac256Driver()->signRaw($data);
    }

    public function hmac256Verify(string $message, string $hmac): bool
    {
        return $this->createHmac256Driver()->verify($message, $hmac);
    }

    public function hmac512Sign(string $data): string
    {
        return $this->createHmac512Driver()->sign($data);
    }

    public function hmac512SignRaw(string $data): string
    {
        return $this->createHmac512Driver()->signRaw($data);
    }


    public function hmac512Verify(string $message, string $hmac): bool
    {
        return $this->createHmac512Driver()->verify($message, $hmac);
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
        if ($this->blake2b === null) {
            $this->blake2b = $this->container->get(Blake2b::class);
        }

        return $this->blake2b;
    }

    public function createHmac512Driver(): HmacSha512
    {
        if ($this->hmac512 === null) {
            $this->hmac512 = $this->container->get(HmacSha512::class);
        }

        return $this->hmac512;
    }

    public function createHmac256Driver(): HmacSha256
    {
        if ($this->hmac256 === null) {
            $this->hmac256 = $this->container->get(HmacSha256::class);
        }

        return $this->hmac256;
    }

    public function createEdDSADriver(): EdDSA
    {
        if ($this->eddsa === null) {
            $this->eddsa = $this->container->get(EdDSA::class);
        }

        return $this->eddsa;
    }
}
