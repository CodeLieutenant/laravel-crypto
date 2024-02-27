<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

use CodeLieutenant\LaravelCrypto\Support\Base64;
use Illuminate\Encryption\MissingAppKeyException;
use RuntimeException;

trait LaravelKeyParser
{
    protected static function parseKey(?string $key, bool $allowEmpty = false): string
    {
        if ($key === null || $key === '') {
            if (!$allowEmpty) {
                throw new MissingAppKeyException();
            }

            return "";
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