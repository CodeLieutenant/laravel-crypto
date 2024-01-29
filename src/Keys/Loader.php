<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Keys;

interface Loader
{
    public function getKey(): string|array;
}