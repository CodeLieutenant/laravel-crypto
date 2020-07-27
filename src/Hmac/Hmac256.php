<?php


namespace BrosSquad\LaravelHashing\Hmac;

use SodiumException;

class Hmac256 extends Hmac
{
    public function sign(string $data): ?string
    {
        try {
            return sodium_crypto_auth($data, $this->keyBinary);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public function verify(string $message, string $hmac): bool
    {
        try {
            return sodium_crypto_auth_verify($hmac, $message, $this->keyBinary);
        } catch (SodiumException $e) {
            return false;
        }
    }
}
