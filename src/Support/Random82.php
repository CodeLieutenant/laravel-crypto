<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Support;

use Random\Randomizer;

class Random82
{
    private static ?Randomizer $randomizer;

    private static function randomizer(): Randomizer
    {
        if (self::$randomizer === null) {
            self::$randomizer = new Randomizer(new Secure());
        }

        return self::$randomizer;
    }

    public static function bytes(int $length): string
    {
        return self::randomizer()->getBytes($length);
    }

    public static function string(int $length): ?string
    {
        return Base64::urlEncodeNoPadding(self::randomizer()->getBytes(Base64::maxEncodedLengthToBytes($length)));
    }

    public static function int(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): ?int
    {
        return self::randomizer()->getInt($min, $max);
    }

}
