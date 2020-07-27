<?php


namespace BrosSquad\LaravelHashing\Facades;


use Illuminate\Support\Facades\Facade;
use BrosSquad\LaravelHashing\HashingManager;

/**
 * Class Hashing
 *
 * @package BrosSquad\LaravelHashing\Facades
 * @method static string|null hash(string $data)
 * @method static boolean equals(string $hash1, string $hash2)
 * @method static string|null sha256(string $data)
 * @method static string|null sha512(string $data)
 */
class Hashing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashingManager::class;
    }
}
