<?php


namespace BrosSquad\LaravelCrypto\Contracts;


interface KeyGeneration
{
    /**
     * Create a new encryption key for the given cipher.
     *
     * @return string
     */
    public static function generateKey(): string;
}
