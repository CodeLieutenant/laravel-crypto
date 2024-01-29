<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encoder;

use RuntimeException;

class IgbinaryEncoder implements Encoder
{
    public function __construct()
    {
        if (!extension_loaded('igbinary')) {
            throw new RuntimeException('igbinary extension is not loaded');
        }
    }

    public function encode(mixed $value): string
    {
        return igbinary_serialize($value);
    }

    public function decode(string $value): mixed
    {
        return igbinary_unserialize($value);
    }
}