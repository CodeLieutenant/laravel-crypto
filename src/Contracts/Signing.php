<?php


namespace BrosSquad\LaravelCrypto\Contracts;

interface Signing
{
    /**
     * Sign the data and return the signature as a base64 url encoded string
     *
     * @param string $data
     *
     * @return string
     */
    public function sign(string $data): string;

    /**
     * Sign the data and return the signature as a raw string (binary)
     *
     * @param string $data
     *
     * @return string
     */
    public function signRaw(string $data): string;

    /**
     * Verify the signature against the $message
     *
     * @param  string  $message
     * @param  string  $hmac
     *
     * @return bool
     */
    public function verify(string $message, string $hmac): bool;
}
