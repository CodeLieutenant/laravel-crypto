<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys\Generators;

interface Generator
{
    public function generate(?string $write): ?string;
}