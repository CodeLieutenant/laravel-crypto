<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

/**
 * Class Hmac512
 *
 * @package BrosSquad\LaravelCrypto\Signing
 */
class Hmac512 extends Hmac
{
    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function sign(string $data): ?string
    {
        return hash_hmac('sha3-512', $data, $this->key);
    }

    public function signRaw(string $data): ?string
    {
        return hash_hmac('sha3-512', $data, $this->key, true);
    }
}
