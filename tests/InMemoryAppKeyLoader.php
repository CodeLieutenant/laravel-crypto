<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests;

use BrosSquad\LaravelCrypto\Keys\Loader;
use Illuminate\Support\Str;

class InMemoryAppKeyLoader implements Loader
{
    public function __construct(private readonly string $key)
    {
    }

    public function getKey(): string|array
    {
        return Str::of($this->key)
            ->remove('base64:', $this->key)
            ->fromBase64()
            ->toString();
    }
}