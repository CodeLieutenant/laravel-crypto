<?php


namespace BrosSquad\LaravelHashing\Facades;

use Exception;
use Illuminate\Support\Facades\Facade;

class Random extends Facade
{
    public static function bytes(int $length): ?string
    {
        try {
            return random_bytes($length);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function string(int $length): ?string
    {
        try {
            $bufferLength = Base64::maxEncodedLengthToBytes($length);
            return rtrim(Base64::urlEncode(random_bytes($bufferLength)), '=');
        } catch (Exception $e) {
            return null;
        }
    }

    public static function hex(int $length): ?string
    {
        $toHex = function_exists('sodium_bin2hex') ? 'sodium_bin2hex' : 'bin2hex';

        try {
            return $toHex(random_bytes(intdiv($length, 2)));
        } catch (Exception $e) {
            return null;
        }
    }

    public static function int(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): ?int
    {
        try {
            return random_int($min, $max);
        } catch (Exception $e) {
            return null;
        }
    }

}
