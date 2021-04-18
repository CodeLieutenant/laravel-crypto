<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Facades;

use BrosSquad\LaravelCrypto\Hashing\HashingManager;
use Illuminate\Support\Facades\Facade;

/**
 * Class Hashing
 *
 * @package BrosSquad\LaravelCrypto\Facades
 *
 * @method static string|null hash(string $data)
 * @method static string|null hashRaw(string $data)
 * @method static boolean equals(string $hash1, string $hash2)
 * @method static string|null sha256(string $data)
 * @method static string|null sha256Raw(string $data)
 * @method static string|null sha512(string $data)
 * @method static string|null sha512Raw(string $data)
 * @method static bool verify(string $hash, string $data)
 * @method static bool verifyRaw(string $hash, string $data)
 * @method static bool sha256Verify(string $hash, string $data)
 * @method static bool sha256VerifyRaw(string $hash, string $data)
 * @method static bool sha512Verify(string $hash, string $data)
 * @method static bool sha512VerifyRaw(string $hash, string $data)
 */
class Hashing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashingManager::class;
    }
}
