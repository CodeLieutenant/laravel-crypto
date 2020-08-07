<?php

declare(strict_types = 1);

namespace BrosSquad\LaravelCrypto\Support;


use SodiumException;
use Illuminate\Support\Facades\Facade;

class Base64
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

    public static function urlEncodeNoPadding(string $binary): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], self::encode($binary));
    }

    public static function urlDecode(string $base64): string
    {
        $str = str_replace(['-', '_'], ['+', '/'], $base64);
        return self::decode($str);
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

    public static function constantUrlEncode(string $binary): ?string
    {
        try {
            return sodium_bin2base64($binary, SODIUM_BASE64_VARIANT_URLSAFE);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public static function constantUrlEncodeNoPadding(string $binary): ?string
    {
        try {
            return sodium_bin2base64($binary, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
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
    public static function constantUrlDecodeNoPadding(string $binary): ?string
    {
        try {
            return sodium_base642bin($binary, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        } catch (SodiumException $e) {
            return null;
        }
    }

    public static function maxEncodedLengthToBytes(int $length): int
    {
        return ((3 * $length) >> 2);
    }


    public static function encodedLengthToBytes(string $base64): ?int
    {
        $count = 0;
        if ($base64[-1] === '=') {
            $count++;
        }

        if ($base64[-2] === '=') {
            $count++;
        }

        return ((3 * strlen($base64)) >> 2) - $count;
    }

    public static function encodedLength(int $bufferLength, bool $hasPadding = true)
    {
        return $hasPadding ? (intdiv(($bufferLength + 2), 3)) << 2 : intdiv((($bufferLength << 2) | 2), 3);
    }
}
