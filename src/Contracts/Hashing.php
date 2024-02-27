<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Contracts;

interface Hashing
{
    /**
     * Hashes the data and returns it as a base64 url encoded string
     *
     * @param string $data
     *
     * @return string
     */
    public function hash(string $data): string;

    /**
     * Hashes the data and returns it as a raw string (binary)
     * * @param string $data
     *
     * @return string
     */
    public function hashRaw(string $data): string;

    /**
     * Verify $hash against $data
     * $hash is a base64 url encoded string
     *
     * @param string $hash
     * @param string $data
     * @return bool
     */
    public function verify(string $hash, string $data): bool;

    /**
     * Verify $hash against $data
     * $hash is a raw string (binary)
     *
     * @param string $hash
     * @param string $data
     * @return bool
     */
    public function verifyRaw(string $hash, string $data): bool;

    /**
     * Check if two strings are equal using constant time comparison
     *
     * @param string $hash1
     * @param string $hash2
     *
     * @return bool
     */
    public function equals(string $hash1, string $hash2): bool;
}
