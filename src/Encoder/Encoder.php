<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Encoder;

interface Encoder
{
    public function encode(mixed $value): string;

    public function decode(string $value): mixed;
}