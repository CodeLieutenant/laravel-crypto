<?php

declare(strict_types=1);

namespace CodeLieutenant\LaravelCrypto\Keys;

interface Generator
{
    public function generate(?string $write): ?string;
}