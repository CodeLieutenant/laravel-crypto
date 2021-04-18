<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Tests;

use BrosSquad\LaravelCrypto\LaravelKeyParser;

trait Key
{
    use LaravelKeyParser;

    protected string $key;

    protected function setKey()
    {
        $this->key = $this->getKey();
    }
}
