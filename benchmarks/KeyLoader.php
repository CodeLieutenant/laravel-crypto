<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Benchmarks;

use BrosSquad\LaravelCrypto\Keys\Loader;

class KeyLoader implements Loader
{
    public function __construct(
        private readonly string $key
    ) {
    }

    public function getKey(): string|array
    {
        return $this->key;
    }
}