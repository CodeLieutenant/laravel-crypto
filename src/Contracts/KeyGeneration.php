<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Contracts;

interface KeyGeneration
{
    /**
     * Create a new encryption key.
     * This key is used to encrypt data.
     * It is recommended to use a key with 32 bytes (256 bits)
     *
     * @param string $cipher
     * @return string
     */
    public static function generateKey(string $cipher): string;
}
