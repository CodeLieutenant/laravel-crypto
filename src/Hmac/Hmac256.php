<?php


namespace BrosSquad\LaravelHashing\Hmac;

use SodiumException;

/**
 * Class Hmac256
 *
 * @package BrosSquad\LaravelHashing\Hmac
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
            return sodium_crypto_auth($data, $this->keyBinary);
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
            return sodium_crypto_auth_verify($hmac, $message, $this->keyBinary);
        } catch (SodiumException $e) {
            return false;
        }
    }
}
