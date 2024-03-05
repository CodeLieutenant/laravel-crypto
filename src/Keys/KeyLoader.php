<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

interface KeyLoader
{
    public function getKey(): string|array;
}