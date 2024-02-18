<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Encoder;

use RuntimeException;

class MessagePackEncoder implements Encoder
{
    public function __construct()
    {
        if (!extension_loaded('msgpack')) {
            throw new RuntimeException('msgpack extension is not loaded');
        }
    }

    public function encode(mixed $value): string
    {
        return msgpack_serialize($value);
    }

    public function decode(string $value): mixed
    {
        return msgpack_unserialize($value);
    }
}