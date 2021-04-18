<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Signing\Hmac;

use SodiumException;
use BrosSquad\LaravelCrypto\Support\Base64;

/**
 * Class Hmac256
 *
 * @package BrosSquad\LaravelCrypto\Signing
 */
class Hmac256 extends Hmac
{

    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function sign(string $data): ?string
    {
        try {
            return Base64::constantUrlEncode(sodium_crypto_auth($data, $this->key));
        } catch (SodiumException $e) {
            return null;
        }
    }


    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function signRaw(string $data): ?string
    {
        try {
            return sodium_crypto_auth($data, $this->key);
        } catch (SodiumException $e) {
            return null;
        }
    }

    /**
     * @param  string  $message
     * @param  string  $hmac
     *
     * @return bool
     */
    public function verify(string $message, string $hmac): bool
    {
        try {
            return sodium_crypto_auth_verify($hmac, $message, $this->key);
        } catch (SodiumException $e) {
            return false;
        }
    }
}
