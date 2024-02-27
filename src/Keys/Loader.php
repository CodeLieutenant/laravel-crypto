<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

interface Loader
{
    public function getKey(): string|array;
}