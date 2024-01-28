<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto;

use BrosSquad\LaravelCrypto\Support\Base64;
use Illuminate\Support\Str;
use RuntimeException;

class LaravelKeyParser
{
    public function __construct()
    {
    }

    /**
     * Parse the encryption key.
     *
     * @param string $key
     * @return string
     */
    public function parseKey(string $key): string
    {
        if (empty($key)) {
            throw new RuntimeException(
                'No application encryption key has been specified.'
            );
        }

        if (Str::startsWith($key, $prefix = 'base64:')) {
            return Base64::decode(Str::after($key, $prefix));
        }

        if (ctype_xdigit($key)) {
            $key = hex2bin($key);
        }

        return $key;
    }

    public function getKey(): string
    {
        return self::parseKey(config('app.key'));
    }
}
