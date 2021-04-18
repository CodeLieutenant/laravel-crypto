<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto;

use Illuminate\Support\Str;
use RuntimeException;

trait LaravelKeyParser
{
    /**
     * Parse the encryption key.
     *
     * @param  array  $config
     *
     * @return string
     */
    protected function parseKey(?string $key): string
    {
        if (empty($key)) {
            throw new RuntimeException(
                'No application encryption key has been specified.'
            );
        }

        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        } elseif (ctype_xdigit($key)) {
            $key = hex2bin($key);
        }

        return $key;
    }

    protected function getKey(): string
    {
        return $this->parseKey(config('app.key'));
    }
}
