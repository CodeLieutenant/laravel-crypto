<?php


namespace BrosSquad\LaravelHashing\Facades;


use Illuminate\Support\Facades\Facade;
use BrosSquad\LaravelHashing\HmacManager;

/**
 * Class Hmac
 *
 * @package BrosSquad\LaravelHashing\Facades
 * @method static string sign(string $data)
 * @method static string hmac256Sign(string $data)
 * @method static string hmac512Sign(string $data)
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
