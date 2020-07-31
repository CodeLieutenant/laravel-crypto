<?php


namespace BrosSquad\LaravelCrypto\Facades;


use BrosSquad\LaravelCrypto\Signing\EdDSA\EdDSAManager;
use Illuminate\Support\Facades\Facade;

/**
 * Class EdDSA
 * @package BrosSquad\LaravelCrypto\Facades
 * @method static string sign(string $data)
 * @method static string signRaw(string $data)
 * @method static boolean verify(string $message, string $hmac)
 */
class EdDSA extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EdDSAManager::class;
    }
}
