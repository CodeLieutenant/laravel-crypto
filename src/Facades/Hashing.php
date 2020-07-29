<?php


namespace BrosSquad\LaravelCrypto\Facades;


use Illuminate\Support\Facades\Facade;
use BrosSquad\LaravelCrypto\HashingManager;

/**
 * Class Hashing
 *
 * @package BrosSquad\LaravelCrypto\Facades
 * @method static string|null hash(string $data)
 * @method static string|null hashRaw(string $data)
 * @method static boolean equals(string $hash1, string $hash2)
 * @method static string|null sha256(string $data)
 * @method static string|null sha256Raw(string $data)
 * @method static string|null sha512(string $data)
 * @method static string|null sha512Raw(string $data)
 */
class Hashing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashingManager::class;
    }
}
