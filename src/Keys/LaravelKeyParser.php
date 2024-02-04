<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

use BrosSquad\LaravelCrypto\Support\Base64;
use Illuminate\Encryption\MissingAppKeyException;
use RuntimeException;

trait LaravelKeyParser
{
    protected static function parseKey(?string $key, bool $allowEmpty = false): string
    {
        if (empty($key) && !$allowEmpty) {
            throw new MissingAppKeyException();
        }

        if (str_starts_with($key, $prefix = 'base64:')) {
            return Base64::decode(substr($key, strlen($prefix)));
        }

        $key = hex2bin($key);

        if ($key === false) {
            throw new RuntimeException('Application encryption key is not a valid hex string.');
        }

        return $key;
    }
}