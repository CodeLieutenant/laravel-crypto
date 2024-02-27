<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Facades;

use CodeLieutenant\LaravelCrypto\Signing\SigningManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string sign(string $data)
 * @method static string signRaw(string $data)
 * @method static string blake2bSign(string $data)
 * @method static string blake2bSignRaw(string $data)
 * @method static string hmac256Sign(string $data)
 * @method static string hmac256SignRaw(string $data)
 * @method static string hmac512Sign(string $data)
 * @method static string hmac512SignRaw(string $data)
 * @method static boolean verify(string $message, string $hmac)
 * @method static boolean blake2bVerify(string $message, string $hmac)
 * @method static boolean hmac256Verify(string $message, string $hmac)
 * @method static boolean hmac512Verify(string $message, string $hmac)
 * @method static string eddsaSign(string $data)
 * @method static string eddsaSignRaw(string $data)
 * @method static bool eddsaVerify(string $message, string $hmac)
 */
class Sign extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SigningManager::class;
    }
}
