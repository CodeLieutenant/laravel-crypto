<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Support;

final class Random
{
    public static function bytes(int $length): ?string
    {
        return random_bytes($length);
    }

    public static function string(int $length): ?string
    {
        $bufferLength = Base64::encodedLength($length, false);
        return Base64::urlEncodeNoPadding(random_bytes($bufferLength));
    }

    public static function int(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): ?int
    {
        return random_int($min, $max);
    }
}
