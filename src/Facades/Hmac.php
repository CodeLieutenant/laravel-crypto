<?php


namespace BrosSquad\LaravelCrypto\Facades;


use Illuminate\Support\Facades\Facade;
use BrosSquad\LaravelCrypto\HmacManager;

/**
 * Class Hmac
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
 */
class Hmac extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HmacManager::class;
    }
}
