<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Facades;

use BrosSquad\LaravelCrypto\Signing\SigningManager;
use Illuminate\Support\Facades\Facade;

/**
 * Class Signing
 *
 * @package BrosSquad\LaravelCrypto\Facades
 * @method static string sign(string $data)
 * @method static string signRaw(string $data)
 * @method static string hmac256Sign(string $data)
 * @method static string hmac256SignRaw(string $data)
 * @method static string hmac512Sign(string $data)
 * @method static string hmac512SignRaw(string $data)
 * @method static boolean verify(string $message, string $hmac)
 * @method static boolean hmac256Verify(string $message, string $hmac)
 * @method static boolean hmac512Verify(string $message, string $hmac)
 * @method static string eddsaSign(string $data)
 * @method static string eddsaSignRaw(string $data)
 * @method static bool eddsaVerify(string $message, string $hmac)
 */
class Signing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SigningManager::class;
    }
}
