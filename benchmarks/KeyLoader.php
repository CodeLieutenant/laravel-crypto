<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Benchmarks;

use CodeLieutenant\LaravelCrypto\Keys\Loader;

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