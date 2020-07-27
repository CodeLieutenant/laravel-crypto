<?php


namespace BrosSquad\LaravelHashing\Facades;


use SodiumException;
use Illuminate\Support\Facades\Facade;

class Base64 extends Facade
{
    public static function encode(string $binary): string
    {
        return base64_encode($binary);
    }

    public static function decode(string $base64): string
    {
        return base64_decode($base64, true);
    }

    public static function urlEncode(string $binary): string
    {
        return str_replace(['+', '/'], ['-', '_'], self::encode($binary));
    }

    public static function urlDecode(string $base64): string
    {
        return str_replace(['-', '_'], ['+', '/'], self::decode($base64));
    }

    public static function constantEncode(string $binary): ?string
    {
        try {
            return sodium_bin2base64($binary, SODIUM_BASE64_VARIANT_ORIGINAL);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public static function constantDecode(string $binary): ?string
    {
        try {
            return sodium_base642bin($binary, SODIUM_BASE64_VARIANT_ORIGINAL);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public static function constantUrlEncode(string $binary): ?string {
        try {
            return sodium_bin2base64($binary, SODIUM_BASE64_VARIANT_URLSAFE);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public static function constantUrlDecode(string $binary): ?string
    {
        try {
            return sodium_base642bin($binary, SODIUM_BASE64_VARIANT_URLSAFE);
        } catch (SodiumException $e) {
            return null;
        }
    }

}
