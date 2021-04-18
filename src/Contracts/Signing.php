<?php


namespace BrosSquad\LaravelCrypto\Contracts;

/**
 * Interface Signing
 *
 * @package BrosSquad\LaravelCrypto\Contracts
 */
interface Signing
{
    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function sign(string $data): ?string;

    /**
     * @param  string  $data
     *
     * @return string|null
     */
    public function signRaw(string $data): ?string;

    /**
     * @param  string  $message
     * @param  string  $hmac
     *
     * @return bool
     */
    public function verify(string $message, string $hmac): bool;
}
